<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\CartaPorte;
use App\Models\Client;
use App\Models\MediosPagos;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use App\Mail\Payment as PaymentEmail;
use App\Models\CurrentAccount;
use App\Models\InvoiceItem;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    use LogsTrait;

    public function index(Request $request)
    {
        $clients = Client::orderBy('id', 'desc')->get();
        $data    = new Payment;
        
        if ($request->client) 
            $data = $data->where('client_id', $request->client);
        
        if ($request->from) 
            $data = $data->whereDate('fecha_pago', '>=', $request->from);
                
        if ($request->to) 
            $data = $data->whereDate('fecha_pago', '<=', $request->to);

        if ($request->status == 'true') 
            $data = $data->whereNotNull('adjunto');

        if ($request->status == 'false') 
            $data = $data->whereNull('adjunto');

        $data = $data->orderBy('id', 'desc')->get();

        return view('user.payment.index', compact('clients', 'data'));
    }
    
    public function create() 
    { 
        $clientes     = Client::pluck('liable', 'id');
        $medios_pagos = MediosPagos::pluck('name', 'id');

        return view('user.payment.create', compact('clientes', 'medios_pagos'));
    }

    public function show(int $id)
    {   
        $data = Payment::findOrFail($id);
        return view('user.payment.show', compact('data'));
    }


    public function edit(int $id) {
        $data         = Payment::findOrFail($id);
        $clientes     = Client::pluck('liable', 'id');
        $medios_pagos = MediosPagos::pluck('name', 'id');

        return view('user.payment.edit', compact('data', 'clientes', 'medios_pagos'));        
    }
  
    public function save(Request $request) 
    {
        $paymentData = $request->except('_token');
        $id = 0;

        if ($request->id) {
            $payment = Payment::find($request->id);

            if(!$payment->update($paymentData)){
                notify()->error('Error al actualizar pago', 'Error');

                return redirect()->route('admin.clients.payment.index');
            }
            $id = $payment->id;
            $this->attachFile($payment->id, $request->adjunto);
        } else {
            $payment = new Payment();

            if(!$payment->create($paymentData)){
                notify()->error('Error al crear pago', 'Error');

                return redirect()->route('admin.clients.payment.index');
            }

            $this->attachFile($payment::latest('id')->first()->id, $request->adjunto);
            $id = $payment::latest('id')->first()->id;
        }
 
        $this->saveAsset($request->client_id, $request->comprobante_id, $request->nro_comprobante, $request->importe);
        $this->general('Cobro', $payment->id, 'Cobro generado', 'Creación/actualización de cobro');
        $this->createFolder();
        $this->sendEmail($id);
        
        notify()->success('Pago generado con éxito', 'Éxito');
        return redirect()->route('admin.clients.payment.index');    
    }

    public function createFolder() {
        if (!is_dir(storage_path().'/app/public/payment')) {
            mkdir(storage_path().'/app/public/payment',0777,true);          
        }
        if (!is_dir(public_path().'/payment')) {
            mkdir(public_path().'/payment',0777,true);
        }
    }

    /**
     * Get all `carta-porte` with status `cerrada` or `facturada`
     * @return JsonResponse
     */
    public function getInvoicedAndClosedCPs(int $clientId)
    {
        $client   = Client::findOrFail($clientId);
        $cartasPorte = CartaPorte::where('json->origen->cuit', $client->CUIT)
                                  ->where('status', 'facturada')
                                  ->with('Invoice')
                                  ->get();
        return $cartasPorte;
    }

    /**
     * Get the `invoice` 
     */
    public function getCPInvoice(int $invoice_id)
    {
        return InvoiceItem::findByInvoiceId($invoice_id)->first()->invoice;
    } 

    private function attachFile(int $payment_id, $file)
    {
        $payment = Payment::find($payment_id);
        if($payment){
            $nameFile = '';
            if ($file) {
                $extFile  = $file->getClientOriginalExtension();        
                $nameFile = Str::random(40).'.'.$extFile;
                $file->storeAs('public/payment/'.$payment_id.'/', $nameFile);
                $payment->update(['adjunto' => $nameFile]);
            }
        }
    }

    private function sendEmail(int $payment_id)
    {
        try {
            $payment = Payment::find($payment_id);
            $email = $payment->client->email;
            Mail::to($email)->send(new PaymentEmail($payment));
        } catch (\Exception $th) {
            throw $th;
        }
    }

    /**
     * save one registry in table `current_account` as `haber`
     *
     * @return void
     */
    private function saveAsset(int $client_id, int $voucher_id, int $voucher_number, float $amount)
    {
        CurrentAccount::create([
            'client_id'      => $client_id,
            'debe'           => null,
            'nro_voucher'    => $voucher_number,
            'invoice_id'     => $voucher_id,
            'haber'          => $amount,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}
