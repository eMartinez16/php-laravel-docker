<?php

namespace App\Http\Controllers\Admin;

require_once(base_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Actions'.DIRECTORY_SEPARATOR.'afipsdk/afip.php/src/Afip.php');
use Afip;
use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\CartaPorte;
use App\Models\Client;
use App\Models\CurrentAccount;
use App\Models\Grain;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Utils\Constants;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoiceController extends Controller
{
    use LogsTrait;
    protected $puntoVenta;
    
    public function __construct()
    {
        $cuit = config('app.CUIT_FACTURACION');
        $this->puntoVenta = intval(config('app.PUNTO_VENTA'));

        $fileKEY = config('app.KEY_BILLING');
        $fileCRT = config('app.CRT_BILLING');
        
        $this->afip = new Afip([
            'CUIT' => $cuit, 
            'production' => false,            
            'cert' => $fileCRT,
            'key' => $fileKEY,
            'res_folder' => base_path().Constants::getCERTPath(),
            'ta_folder' => base_path().Constants::getTAPath(),
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $cps = [];
        $ports = Constants::getPorts();
        $clients = Client::pluck('business_name', 'cuit');

        if($req->client_id){
            $cps = CartaPorte::where('status', 'cerrada')
                    ->with('downloadTicket')
                    ->where('json->origen->cuit', $req->client_id);

            if($port = $req->port){
                $cps = $cps->whereHas('downloadTicket', function($q) use ($port) {
                            $q->where('port','LIKE', '%'.$port.'%');
                        });
            }
            
            if($req->fecha_inicio && $req->fecha_fin) {
                $cps = $cps->whereDate('cartas_portes.created_at','>=',$req->fecha_inicio)
                            ->whereDate('cartas_portes.created_at','<=',$req->fecha_fin);   
                }  
    
            $cps = $cps->get();            
        }

        return view('admin.carta_porte.invoice.index', compact('cps', 'ports', 'clients'));
    }

    /**
     * generate voucher in afip and stores in db
     *
     * @param Request $req
     */
    public function createInvoice(Request $req)
    {
        if($req->isMethod('POST')){
            $cps = explode(',',$req->cps);
            $total = 0;
            $totalIva = 0;
            $totalNet  = 0;
            $invoiceItems = [];

            foreach($cps as $cp){
                $cpInfo = CartaPorte::findByNumber($cp)->first();
                $cuitClient = intval($cpInfo->getJson()->origen->cuit);

                $client = Client::findByCUIT($cuitClient)->first();

                //get the last `tarifa` value of the client category of the CP
                $rate = $client->category->rate->last()->value;

                //net_total
                $subtotalItem = $cpInfo->downloadTicket->commercial_net * $rate;

                //iva value foreach item
                $iva  = ($subtotalItem * 21) / 100;
                
                $totalItem    = $subtotalItem + $iva;

                $invoiceItems[] = [
                    'carta_porte_id' => $cpInfo->id,
                    'neto'           => $subtotalItem,
                    'iva'            => $iva,
                    'total'          => $totalItem
                ];

                //for the invoice
                $totalIva += $iva;
                $totalNet += $subtotalItem;
                $total    += $totalItem;
            }
            /**
             *  (dependiendo tipo de condicion fiscal, siempre llega responsable inscripto)
             * 80 CUIT
             * 86 CUIL
             * 96 DNI
             */
            $tipo_doc = 80;

            /**
             * documento de cliente a facturar (se puede usar la variable $cuitClient, verificar que la sincro traiga los mismos datos que la CP
             * campo origen->cuit y cliente->cuit)
             */
            //pruebas
            //  $nro_doc  = 31312321131;
            $nro_doc  = $cuitClient;


            $data = [
                'CantReg' 	=> 1,  //! Cantidad de comprobantes a registrar 
                'PtoVta' 	=> $this->puntoVenta,  // Punto de venta (conf environment)
                'CbteTipo' 	=> 1,  // Tipo de comprobante (ver tipos disponibles) 1- FACTURA A
                'Concepto' 	=> 2,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                'DocTipo' 	=> $tipo_doc, // Tipo de documento del comprador 
                'DocNro' 	=> $nro_doc,  // Número de documento del comprador 
                'CbteDesde' => ($this->afip->ElectronicBilling->getLastVoucher($this->puntoVenta, 1)) + 1, //last voucher number
                'CbteHasta' => ($this->afip->ElectronicBilling->getLastVoucher($this->puntoVenta, 1)) + 1, //last voucher number
                'CbteFch' 	=> intval((now())->format('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal' 	=> round($total, 2), // Importe total del comprobante 
                'ImpTotConc'=> 0,   // Importe neto no gravado 
                'ImpNeto' 	=> $totalNet, // Importe neto gravado 
                'ImpOpEx' 	=> 0,   // Importe exento de IVA 
                'ImpIVA' 	=> round($totalIva,2),  // Importe total de IVA 
                'ImpTrib' 	=> 0,   // Importe total de tributos
                'MonId' 	=> 'PES', //! Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
                'MonCotiz' 	=> 1,     //! Cotización de la moneda usada (1 para pesos argentinos)  
                'Iva' 		=> [
                    'Id' => 5,
                    'BaseImp' => $totalNet,
                    'Importe' => round($totalIva,2),
                ], // (Opcional) Alícuotas asociadas al comprobante     
                'FchServDesde' => intval((now())->format('Ymd')),   //* Si es Servicio o Productos y Servicios/ dia facturado
                'FchServHasta' => intval((now()->addDays(30))->format('Ymd')),   //* 30 dias dsp
                'FchVtoPago'   => intval((now()->addDays(15))->format('Ymd')),   //* 15 dias dsp
            ];

            try {
                $invoice = $this->afip->ElectronicBilling->CreateNextVoucher($data);

                if($invoice['CAE'] > 0){
                    //store invoice and invoice items in our system
                    $newInvoice = [
                        'voucher_number'    => $invoice['voucher_number'],
                        'voucher_type'      => $data['CbteTipo'],
                        'sale_point'        => $data['PtoVta'],
                        'cae'               => $invoice['CAE'],
                        'cae_expiration'    => $invoice['CAEFchVto'],
                        'voucher_from'      => $data['FchServDesde'],
                        'voucher_to'        => $data['FchServHasta'],
                        'voucher_expiration'=> $data['FchVtoPago'],
                        'concept'           => 'Servicios',
                        'total_net'         => $data['ImpNeto'],
                        'total_exempt'      => $data['ImpOpEx'],
                        'total_iva'         => $data['ImpIVA'],
                        'total'             => $data['ImpTotal'],
                        'date'              => now()
                    ];
    
                    if( ($voucher = new Invoice())->create($newInvoice)) {
                        foreach($cps as $cp){                                                        
                            $cp = CartaPorte::findByNumber(intval($cp))->first();

                            if($cp){
                                //no me funcionó pasar en el array del método update el status 🤔
                                $cp->status = 'facturada';

                                if(!$cp->save()){
                                    notify()->error('Error al actualizar carta de porte.', 'Error');
                                }                                
                            }
                        }

                        //si se crea la factura, cargo los items de la misma
                        foreach ($invoiceItems as $item) {
                            $newItem = new InvoiceItem();
                            $item['invoice_id'] = $voucher::latest('id')->first()->id;

                            if(!$newItem->create($item)){
                                notify()->error('Error al almacenar item de factura. INFO: '.json_decode($newItem), 'Error');
                            }
                        }

                        $this->saveDebit($client->id, $voucher::latest('id')->first()->id, $newInvoice['voucher_number'],  $newInvoice['total']);
                        $this->creates('Factura', $voucher::latest('id')->first()->id, 'Factura creada');
                        notify()->success('Factura creada correctamente', 'Éxito');
                        return back();  
                    }else{
                        notify()->error('Error al crear factura.', 'Error');
                        return redirect()->route('admin.invoices.index');  
                    }
                }
            } catch (\Exception $th) {
                throw $th;
            }
            return redirect()->route('admin.invoices.index');
        }
    }

    /**
     * download invoice in pdf
     *
     * @param integer $cp_id cartaporte id
     */
    public function downloadPDF(int $cp_id){
        $cp = CartaPorte::find($cp_id);
        $client = Client::findByCUIT($cp->getJson()->origen->cuit)->first();
        $invoice = $cp->invoice;

        //DATOS VYV
        $invoice->cuit                = config('app.CUIT_PRODUCCION');
        $invoice->punto_venta         = $this->puntoVenta;
        $invoice->fecha_inicio_act    = config('app.INICIO_ACT');
        $invoice->razon_social        = config('app.RAZON_SOCIAL');
        $invoice->domicilio_comercial = config('app.DOMICILIO_COMERCIAL');
        $invoice->condicion_fiscal    = config('app.CONDICION_FISCAL');

        view()->share('invoice', $invoice);
        $pdf = PDF::loadView('admin.carta_porte.invoice.pdf', ['invoice'=>$invoice, 'cp'=>$cp, 'client'=>$client]);
        return $pdf->download('comprobante_'.$invoice->voucher_number.'.pdf');
    }

    /**
     * save one registry in table `current_account` as `debe`
     *
     * @return void
     */
    private function saveDebit(int $client_id, int $voucher_id, int $voucher_number, float $amount)
    {
        CurrentAccount::create([
            'client_id'      => $client_id,
            'debe'           => $amount,
            'nro_voucher'    => $voucher_number,
            'invoice_id'     => $voucher_id,
            'haber'          => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}
