<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

require_once(base_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Actions'.DIRECTORY_SEPARATOR.'afipsdk/afip.php/src/Afip.php');
use Afip;
use App\Models\CartaPorte;
use Carbon\Carbon;
use App\Http\Traits\LogsTrait;
use App\Models\Client;
use App\Models\Grain;
use App\Utils\Constants;

class CartaPorteController extends Controller
{
    use LogsTrait;
    
    protected $afip;        
    protected $tipo = [
      'CP_AUTO' => 74,
      'CP_FERRO' => 75,
      'CP_FLETE' => 99
    ];
    
    public function __construct()
    {        
      $cuit = config('app.CUIT_PRODUCCION');
      $puntoVenta = intval(config('app.PUNTO_VENTA'));

      $fileKEY = config('app.KEY_PROD');
      $fileCRT = config('app.CRT_PROD');
        
    
      $this->afip = new Afip([
        'CUIT' => $cuit, 
        'production' => true,            
        'cert' => $fileCRT,
        'key' => $fileKEY,
        'res_folder' => base_path().Constants::getCERTPath(),
        'ta_folder' => base_path().Constants::getTAPath(),
      ]);
           
    }

    public function indexAuto(Request $request) {               
      $tipo = $this->tipo['CP_AUTO'];
    //   dd($this->afip->ElectronicBilling->GetVoucherTypes());

      $data = CartaPorte::where('tipo',$tipo)->orderBy('id','desc');
      if($request->estado) {
        $data = $data->where('estado', $request->estado);
      }  
      if($request->fecha_inicio && $request->fecha_fin) {
        $data = $data->whereDate('emision','>=',$request->fecha_inicio)
                     ->whereDate('emision','<=',$request->fecha_fin);   
      }        

      if($request->ctg){
        $data = $data->where('ctg', $request->ctg);
      }
   
      if($cuit = $request->solicitante){
        $data = $data->where('json->origen->cuit', $cuit);            
      }

      $data = $data->paginate(20);

      $estados = CartaPorte::select('estado')->groupBy('estado')->get()->toArray();
      
      $str_tipo = "Automotor";      

      return view('admin.carta_porte.index', compact('data', 'str_tipo', 'estados'));
    }
    
    public function indexFerro(Request $request) {        
      $tipo = $this->tipo['CP_FERRO'];                 

      $data = CartaPorte::where('tipo',$tipo)->orderBy('id','desc');
      if($request->estado) {
        $data = $data->where('estado', $request->estado);
      }  

      if($request->fecha_inicio && $request->fecha_fin) {
        $data = $data->whereDate('emision','>=',$request->fecha_inicio)
                     ->whereDate('emision','<=',$request->fecha_fin);   
      }

      if($request->ctg){
        $data = $data->where('ctg', $request->ctg);
      }
   
      if($cuit = $request->solicitante){
        $data = $data->where('json->origen->cuit', $cuit);            
      }

      $data = $data->paginate(20);


      $estados = CartaPorte::select('estado')->groupBy('estado')->get()->toArray();

      $str_tipo = "Ferroviarias";

      return view('admin.carta_porte.index', compact('data', 'str_tipo', 'estados'));
    }

    public function show($id) {
      $data = CartaPorte::find($id);

      if($data->tipo == $this->tipo['CP_AUTO'])
        $str_tipo = "Automotor";   
      else 
        $str_tipo = "Ferroviarias";

      return view('admin.carta_porte.show', compact('data', 'str_tipo'));
    }

    public function forceSaveAuto(Request $request) {
      if(!is_dir(storage_path().'/app/public/afip')) {
        mkdir(storage_path().'/app/public/afip',0777,true);          
      }      
      if(!is_dir(public_path().'/afip')) {
        mkdir(public_path().'/afip',0777,true);          
      }                  

      try {          
        $cuit = $request->cuit;
        $tipo = $this->tipo['CP_AUTO'];
        $sucursal = $request->sucursal;
        $numero = $request->numero;
        $ctg = $request->ctg; //actualmente solo esta consultando por este dato
        
        $cp = $this->afip->CartaDePorte->consultarCPEAutomotor($cuit, $tipo, $sucursal, $numero, $ctg)->respuesta;        
        
        $folder_st = storage_path().'/app/public/afip/';          
        $folder_pb = public_path().'/afip/';          
        $file = $tipo.'_'.$sucursal."_".$numero.".pdf";

        file_put_contents($folder_st.$file,$cp->pdf);          
        file_put_contents($folder_pb.$file,$cp->pdf);          
        
        $cp->file_pdf_st = asset('storage/afip/'.$file);
        $cp->file_pdf_pb = asset('afip/'.$file);          
                  
        $arrays = [];
        foreach($cp as $key => $object){     
          if($key != 'pdf')       
            $arrays[$key] = $object;
        }

        $existe = CartaPorte::where('tipo', $tipo)->where('numero',$cp->cabecera->nroOrden)->first();          
        if(!$existe) {
          $_cp = new CartaPorte;
          $_cp->tipo = $tipo;
          $_cp->sucursal = $cp->cabecera->sucursal;
          $_cp->numero = $cp->cabecera->nroOrden;
          $_cp->ctg = $cp->cabecera->nroCTG;
          $_cp->emision = $cp->cabecera->fechaEmision;
          $_cp->estado = $cp->cabecera->estado;
          $_cp->pdf = $cp->file_pdf_pb;
          $_cp->json = json_encode($arrays);
          $_cp->save();   
        } else {            
          $_cp = $existe;
          $_cp->tipo = $tipo;
          $_cp->sucursal = $cp->cabecera->sucursal;
          $_cp->numero = $cp->cabecera->nroOrden;
          $_cp->ctg = $cp->cabecera->nroCTG;
          $_cp->emision = $cp->cabecera->fechaEmision;
          $_cp->estado = $cp->cabecera->estado;
          $_cp->pdf = $cp->file_pdf_pb;
          $_cp->json = json_encode($arrays);
          $_cp->save();             
        }

        $this->general('Carta Porte Automotor', 0, 'CP sincronizada con éxito', 'Sincronización Masiva');

        return redirect()->route('admin.carta_porte.indexAuto');
                
      } catch (\Exception $e) {    
        dd($e);

        notify()->error($e->getMessage(),"Error: ","topRight");
        return redirect()->route('admin.carta_porte.indexAuto');        
      }      
    }

    public function forceSaveFerro(Request $request) {
      if(!is_dir(storage_path().'/app/public/afip')) {
        mkdir(storage_path().'/app/public/afip',0777,true);          
      }      
      if(!is_dir(public_path().'/afip')) {
        mkdir(public_path().'/afip',0777,true);          
      }           

      try {          
        $cuit = $request->cuit;            
        $tipo = $this->tipo['CP_FERRO'];
        $sucursal = $request->sucursal;
        $numero = $request->numero; 
        $ctg = $request->ctg; //actualmente solo esta consultando por este dato
                 
        $cp = $this->afip->CartaDePorte->consultarCPEFerroviaria($cuit, $tipo, $sucursal, $numero, $ctg)->respuesta;
        
        $folder_st = storage_path().'/app/public/afip/';   
        $folder_pb = public_path().'/afip/';         
        $file = $tipo.'_'.$sucursal."_".$numero.".pdf";

        file_put_contents($folder_st.$file,$cp->pdf);
        file_put_contents($folder_pb.$file,$cp->pdf);                    
        
        $cp->file_pdf_st = asset('storage/afip/'.$file);
        $cp->file_pdf_pb = asset('afip/'.$file);          
                  
        $arrays = [];
        foreach($cp as $key => $object){     
          if($key != 'pdf')       
            $arrays[$key] = $object;
        }

        $existe = CartaPorte::where('tipo', $tipo)->where('numero',$cp->cabecera->nroOrden)->first();
        if(!$existe) {
          $_cp = new CartaPorte;
          $_cp->tipo = $tipo;          
          $_cp->sucursal = $cp->cabecera->sucursal;
          $_cp->numero = $cp->cabecera->nroOrden;
          $_cp->ctg = $cp->cabecera->nroCTG;
          $_cp->emision = $cp->cabecera->fechaEmision;
          $_cp->estado = $cp->cabecera->estado;
          $_cp->pdf = $cp->file_pdf_pb;
          $_cp->json = json_encode($arrays);            
          $_cp->save();          
        } else {
          $_cp = $existe;
          $_cp->tipo = $tipo;          
          $_cp->sucursal = $cp->cabecera->sucursal;
          $_cp->numero = $cp->cabecera->nroOrden;
          $_cp->ctg = $cp->cabecera->nroCTG;
          $_cp->emision = $cp->cabecera->fechaEmision;
          $_cp->estado = $cp->cabecera->estado;
          $_cp->pdf = $cp->file_pdf_pb;
          $_cp->json = json_encode($arrays);            
          $_cp->save();                      
        }     

        $this->general('Carta Porte Ferroviaria', 0, 'CP sincronizada con éxito', 'Sincronización Masiva');

        return redirect()->route('admin.carta_porte.indexFerro');
      } catch (\Exception $e) {         
        notify()->error($e->getMessage(),"Error: ","topRight");
        return redirect()->route('admin.carta_porte.indexFerro');
      }
    }  

    /** Get all the cp `Cerradas` (that have one `ticket de descarga`)
     * 
     */
    public function getCPClosed()
    {
        $clients = Client::pluck('business_name', 'id');
        $cps = [];

        if ($clientId = request()->client_id) {
            $clientCuit = Client::select('cuit')
                                 ->where('id', $clientId)
                                 ->first();

            if (is_null($clientCuit)) return back()->with('error', 'No se encontró información del cliente');

            $yesterday = Carbon::now()
                        ->add(-1, 'day')
                        ->format('Y-m-d');

            $cps = CartaPorte::where('status', 'cerrada')
                              ->with('downloadTicket')
                              ->whereHas('downloadTicket', function($q) use ($yesterday) {
                                    $q->whereDate('created_at', $yesterday);
                                })
                              ->where('json->origen->cuit', $clientCuit->cuit)
                              ->paginate(20);
        }

        if(request()->download) return $this->downloadCSV($cps, request()->client_id);

        return view('admin.carta_porte.report', compact('cps', 'clients'));
    }

    /**
     * @param array|CartaPorte[]
     * @param int $clientCuit
     */
    public function downloadCSV($cps, $clientCuit)
    {
        $headers = ['Content-Type' => 'text/csv'];

        $fields = [
            'Nro',
            'CTG',
            'Solicitante',
            'Emisión',
            'Vto',
            'Estado',
            'Transportista',
            'Ticket de descarga',
        ];

        $data = '';
        foreach ($cps as $value) {
            $data .= $value->numero.';'.$value->ctg.';'.$value->getJson()->destinatario->cuit.';'.Carbon::createFromFormat('Y-m-d\TH:i:s', $value->emision)->format('d/m/Y').';'.Carbon::createFromFormat('Y-m-d\TH:i:s', $value->getJson()->cabecera->fechaVencimiento)->format('d/m/Y').';'.$value->estado.';'.$value->getJson()->transporte->cuitTransportista.';'.$value->getTicketAttribute()['nro_ticket']."\r\n";
        }

        return response()->streamDownload(function () use ($fields, $data) {
            $line  = implode(';', $fields);
            $line .= "\r\n";
            $line .= $data;
            echo $line;
        }, 'reporte_cps_'.$clientCuit.'.csv', $headers);
    }

    /**
     * create form to store a CP. Needs the type of CP (`Automotor`, `Ferroviaria`)
     *
     * @param string $type
     */
    public function create(string $type){        
        if ($type == 'auto') {
            $type = $this->tipo['CP_AUTO'];
        }else if ($type == 'ferro') {
            $type = $this->tipo['CP_FERRO'];
        }else{
            $type = $this->tipo['CP_FLETE'];
        }

        $provinces = $this->afip->CartaDePorte->getProvincias();
        $grains    = Grain::pluck('name', 'id');

        return view('admin.carta_porte.store', compact('type', 'provinces', 'grains'));
    }

    /**
     * store a CP manually
     *
     * @return void
     */
    public function store(Request $request)
    {
        if($request->isMethod('POST')){
            if($request->tipo == 'ferro'){
                $request->tipo = 75;
            }elseif ($request->tipo == 'auto'){
                $request->tipo = 74;
            }

            $cpData = [
                'estado'  => $request->estado,
                'tipo'    => $request->tipo,
                'numero'  => (CartaPorte::findLastCPNumber()->first())->numero + 1,
                'sucursal'=> $request->sucursal,
                'ctg'     => $request->ctg,
                'emision' => $request->fecha_emision,
                'pdf'     => '-',
                'json'    => $this->jsonFormat($request)
            ];

            if(sizeof($cpData)){
                if(!CartaPorte::create($cpData)){
                    notify()->error('Error al crear carta de porte.', 'Error', 'topRight');
                    return back();  
                }

                $this->creates('Carta de porte', CartaPorte::latest('id')->first()->id, 'Creada manualmente');
                notify()->success('Carta de porte creada correctamente.', 'Éxito', 'topRight');
                return back(); 
            }
        }
    }

    /**
     * import CTGs from csv
     * @param Request $req
     */
    public function importCTG(Request $req)
    {
        try {
            ini_set('max_execution_time', 0);
            ini_set("memory_limit", "-1");

            if ($req->isMethod('POST') && $req->hasFile('archivo')) {      
                $messages  = [];
                $delimiter = ';';
                $file      = $req->file('archivo');
                $types     = ['text/plain'];
    

                if (in_array($file->getClientMimeType(), $types)) {
                    if (($txtFile = fopen($file, 'r')) !== FALSE) {     
                        $row  = 0;
                        /**
                         * almacena todos los ctg y fechas del txt
                         * @var array $txtData
                         */
                        $txtData = [];

                        //tres meses atras a partir de hoy
                        $threeMonthEarly =  strtotime(date("d-m-Y H:i:00", time())."-3 month");

                        while (($line = fgetcsv($txtFile, 1000, $delimiter)) !== FALSE) {                           
                            $number = count($line);
                            $row++;

                            if ($row == 1) continue;              
                            if ($number <= 1) {
                                throw new \Exception('El archivo no posee un formato valido, corrobore que el delimitador del archivo sea el seleccionado y vuelva a intentarlo');
                            }                            
                            if ($number < 3) {
                                throw new \Exception("A la fila nro. $row le faltan columnas, por favor verifique la que la cantidad sea la misma que en el ejemplo y vuelva a intentarlo");
                            }

                            //salto los headers
                            if($row > 1){
                                $cpDate = strtotime($line[2]);
                                //Solamente me guardo el campo `CPE` que corresponde al `ctg` de CP de los ultimos 3 meses
                                if($cpDate > $threeMonthEarly ){
                                    $txtData[] = ['CTG'=> $line[0], 'fecha'=> $line[2]];
                                }
                            }
                        }

                        //Elimino la cabecera (CPE)
                        unset($txtData[0]); 
                        $tipoCP = $req->tipo;
              
                        foreach ($txtData as $data) {
                            try {
                                $check = $this->checkCTG($data['CTG'], $tipoCP);

                                if(!$check) $messages[] =  'Error al importar CP. CTG:'.$data['CTG'];

                            } catch (\Exception $th) {
                                throw $th;
                            }                           
                        }

                        if (empty($messages)) {
                            $this->general('Carta de porte', 0, 'Importados correctamente', 'Importación CTG');
                            notify()->success("La importación se realizó con éxito.", "Éxito: ", "topRight");
                        } else {
                            if (count($messages) == count($data)) {
                                notify()->error("La información contenida en el archivo es errónea.", "Error: ", "topRight");
                            } else {
                                notify()->warning("La importación se realizó de forma parcial.", "Aviso: ", "topRight");
                            }
                        }            
                    } else {
                        throw new \Exception('No se pudo leer el archivo. Vuelva a intentarlo.');
                    }
                } else {
                    throw new \Exception('El archivo tiene un formato incompatible. Solo se admite ".txt"');
                }
            } else {
                throw new \Exception('No se ha enviado archivo, recuerde que solo se admite ".txt"');
            }
        } catch (\Throwable $th) {
            notify()->error($th->getMessage(), "Error: ", "topRight");
        } finally {
            if (!empty($txtFile)) {
                fclose($txtFile);
                unset($txtFile);
            }
        }

        return redirect()->route('admin.carta_porte.indexAuto');
    }

    /**
     * check if there is a CP with the CTG sent, if it does not exist it creates it synchronizing with afip
     * return false when errors happens
     * @param integer $ctg
     * @param string $tipo
     * @return boolean
     */
    private function checkCTG(int $ctg, string $tipo)
    {
        $existCP = CartaPorte::findByCTG($ctg)
                    ->where('tipo', $tipo)
                    ->first();
        $success = true;
        if(is_null($existCP)){
            if($tipo == 'auto'){
                $cp = $this->afip->CartaDePorte->consultarCPEAutomotor(null, null, null, null, $ctg)->respuesta; 
                $tipo = $this->tipo['CP_AUTO'];
            }else{
                $cp = $this->afip->CartaDePorte->consultarCPEFerroviaria(null, null, null, null, $ctg)->respuesta;
                $tipo = $this->tipo['CP_FERRO'];

            }

            $jsonData = [];
            foreach($cp as $key => $object){     
                if($key != 'pdf')       
                    $jsonData[$key] = $object;
            }

            try {   
                $folder_st = storage_path().'/app/public/afip/';   
                $folder_pb = public_path().'/afip/';         
                $file = $tipo.'_'.$cp->cabecera->sucursal."_". $cp->cabecera->nroOrden.".pdf";

                file_put_contents($folder_st.$file,$cp->pdf);
                file_put_contents($folder_pb.$file,$cp->pdf);                                    
                $cp->file_pdf_st = asset('storage/afip/'.$file);
                $cp->file_pdf_pb = asset('afip/'.$file);      

                $newCP = new CartaPorte;
                $newCP->tipo = $tipo;
                $newCP->sucursal = $cp->cabecera->sucursal;
                $newCP->numero = $cp->cabecera->nroOrden;
                $newCP->ctg = $cp->cabecera->nroCTG;
                $newCP->emision = $cp->cabecera->fechaEmision;
                $newCP->estado = $cp->cabecera->estado;
                $newCP->pdf = $cp->file_pdf_pb;
                $newCP->json = json_encode($jsonData);
                
                (!$newCP->save()) 
                ?  $success = false
                :  $success = true;
            } catch (\Exception $th) {
                throw $th;
            }
        }

        return $success;
    }

    /**
     * search localities by province_id (ALL USING AFIP SDK)
     *
     * @param integer $cod_province
     * @return array
     */
    public function getLocalitiesByProvince(int $cod_province){
        return $this->afip->CartaDePorte->getLocalidadesPorProvincia($cod_province)->respuesta->localidad;
    }
     
    /**
     * data formatting in json
     *
     * @param Request $request
     * @return array
     */
    private function jsonFormat(Request $request){
        /**
         * NRO ORDEN viene de la afip, por ende no lo utilizamos
         */
        ($request->mercaderiaFumigada) ? $mercaderia = true : $mercaderia = false;  

        return json_encode([
            "cabecera" => [
                'tipoCartaPorte'    => $request->tipo,
                'sucursal'          => $request->sucursal,
                'nroOrden'          => '-',
                'nroCTG'            => $request->ctg,
                'fechaEmision'      => $request->fecha_emision,
                'estado'            => $request->estado,
                'fechaInicioEstado' => $request->fecha_inicio_estado,
                'fechaVencimiento'  => $request->fecha_vencimiento,
                'observaciones'     => $request->observaciones,                
            ],
            "origen" => [
                'cuit'          => $request->cuit_origen,
                'codProvincia'  => $request->provincia_origen,
                'codLocalidad'  => $request->localidad_origen,
                'planta'        => $request->planta_origen,
            ],
            "correspondienteRetiroProductor" => false,
            "retiroProductor"                => [],
            "intervinientes" => [
                'cuitCorredorVentaPrimaria'   => $request->cuit_remitente,
                'cuitCorredorVentaSecundaria' => $request->cuit_corredor,
                'cuitRepresentanteEntregador' => $request->cuit_representante,                
            ],
            "datosCarga" => [
                'codGrano'         => $request->grain_id,
                'pesoBruto'        => $request->peso_bruto,
                'pesoTara'         => $request->peso_tara,
                'cosecha'          => $request->cosecha,
                'pesoTaraDescarga' => $request->peso_tara_descarga,
                'pesoBrutoDescarga'=> $request->peso_bruto_descarga
            ],
            "destino" => [
                'cuit'         => $request->cuit_destino,
                'codProvincia' => $request->provincia_destino,
                'codLocalidad' => $request->localidad_destino,
                'planta'       => $request->planta_destino,
            ],
            "destinatario" => [
                'cuit' => $request->destinatario,
            ],
            "transporte"  => [
                'cuitTransportista' => $request->cuit_transportista,
                'dominio'           => $request->dominio, 
                'fechaHoraPartida'  => $request->fecha_partida,
                'kmRecorrer'        => $request->km_recorrer,
                'codigoTurno'       => $request->codigo_turno,
                'cuitChofer'        => $request->cuit_chofer,
                'tarifaReferencia'  => $request->tarifa_referencia,
                'tarifa'            => $request->tarifa,
                'cuitPagadorFlete'  => $request->cuit_pagador_flete,
                'mercaderiaFumigada'=> $mercaderia,
            ],
        ]);
    }
}