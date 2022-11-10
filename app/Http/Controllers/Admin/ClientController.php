<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\ClientCategory;
use Illuminate\Http\Request;
use App\Http\Traits\LogsTrait;

require_once(base_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Actions'.DIRECTORY_SEPARATOR.'afipsdk/afip.php/src/Afip.php');
use Afip;
use App\Models\CondicionFiscal;
use App\Utils\Constants;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    use LogsTrait;

    protected $afip;
    protected $cuit;

    protected $payment_condition = array(
      'contado' => 'Contado',
      '15_dia' => 'CtaCte 15 días',
      '30_dia' => 'CtaCte 30 días',      
    );
        
    public function __construct()
    {
      $this->cuit = config('app.CUIT_PRODUCCION');      
      
      $fileKEY = config('app.KEY_PROD');
      $fileCRT = config('app.CRT_PROD');
        
      $this->afip = new Afip([
        'CUIT' => $this->cuit, 
        'production' => true,            
        'cert' => $fileCRT,
        'key' => $fileKEY,
        'res_folder' => base_path().Constants::getCERTPath(),
        'ta_folder' => base_path().Constants::getTAPath(),
      ]);        
    }

    public function index(Request $request)
    {
      if ($request->enabled == 'todos') {
        $request->enabled = null;
      }
      $act = $request->enabled ?? null;
      $clients = Client::when($request->search , function ($query) use ($request) {
        return $query->where(function ($query) use ($request) {
          return $query
          ->orWhere('id', $request['search'])
          ->orWhere('business_name', 'LIKE', '%'.$request['search'].'%' )
          ->orWhere('email', 'LIKE', '%'.$request['search'].'%' )
          ->orWhere('CUIT', 'LIKE', '%'.$request['search'].'%' )
          ->orWhere('phone', 'LIKE', '%'.$request['search'].'%' );
        });
      })->when($request->enabled, function ($query) use ($request) {
        return $query->where('disabled', $request->enabled);
      })->paginate(50);
      
      $enabled = [
        '0' => 'Todos',
        'null' => 'Habilitado',
        '1' => 'Deshabilitado'
      ];
      $search = $request->search ?? null;
      
      return view('user.clients.index', compact('clients', 'search', 'enabled', 'act'));
    }

    public function store(ClientRequest $request)
    {      
        if($request->validated()){            
            try {
              $client = new Client();
              $request = $request->validated();
              $request['fiscal_condition_id'] = $this->getCondicionFiscal($this->afip->RegisterScopeFive->GetTaxpayerDetails($request['CUIT']));
              $client->create($request);
                    
              $this->creates('Clientes', Client::latest('id')->first()->id, 'Cliente creado con éxito');

              notify()->success('Cliente creado correctamente.', 'Éxito', 'topRight');   
              return redirect()->route('admin.clients.index');                  
            } catch (\Exception $th) {
              $name = class_basename($this);
              Log::error('Error en linea: '.$th->getLine());
              Log::error('Controller: '.$name);

              $error = 'El CUIT de la persona es inválido';
              notify()->error($error, 'Error', 'topRight');
              return redirect()->route('admin.clients.index');  
            }                              
        }else{
            notify()->error($request->errors(), 'Error', 'topRight');
            return redirect()->route('admin.clients.index');
        }
    }

    public function create(){
        $categories = ClientCategory::pluck('name', 'id');

        $payment_condition = $this->payment_condition;

        return view('user.clients.create', compact('categories', 'payment_condition'));
    }


    public function show(int $id)
    {   
        $client = Client::findOrFail($id);

        return view('user.clients.show', compact('client'));
    }


    public function edit(int $id)
    {
        $client = Client::findOrFail($id);
        $categories = ClientCategory::pluck('name', 'id');

        $payment_condition = $this->payment_condition;

        return view('user.clients.edit', compact('client', 'categories', 'payment_condition'));
    }


    public function update(ClientRequest $request, int $id)
    {   
        if($request->isMethod('patch')){            
            $client = Client::findOrFail($id);
            if(!$request->validated()){
                notify()->error($request->errors(), 'Error', 'topRight');
                return redirect()->route('admin.clients.show', $id);
            }        

            if(!$client->update($request->validated())){
                notify()->error('Error al actualizar cliente.', 'Error', 'topRight');
                return redirect()->route('admin.clients.show', $id);
            }else{                
                $client->fiscal_condition_id = $this->getCondicionFiscal($this->afip->RegisterScopeFive->GetTaxpayerDetails($client->CUIT));
                $client->save();

                $this->updates('Clientes', $client->id, 'Cliente actualizado con éxito');

                notify()->success('Cliente actualizado correctamente.', 'Éxito', 'topRight');
                return redirect()->route('admin.clients.show', $id);           
            }
        }else{
            return response('Invalid method',500);
        }
    }

    public function disabled(Request $request) {
        try {

          $client = Client::findOrFail($request->id);
          $client->disabled = 1;
          $client->save();

          $this->general('Clientes', $client->id, 'Cliente deshabilitado con éxito', 'Deshabilitar');

          notify()->success('Cliente deshabilitado correctamente.', 'Éxito', 'topRight');
          return redirect()->route('admin.clients.index');  
        } catch (\Exception $e) {
          return back()->with('error',$e->getMessage());
        }
    }
    
    public function enabled(Request $request) {
        try {

          $client = Client::findOrFail($request->id);
          $client->disabled = false;
          $client->save();
          
          $this->general('Clientes', $client->id, 'Cliente habilitado con éxito', 'Habilitar');

          return back()->with('message',"Cliente habilitado correctamente");
        } catch (\Exception $e) {
          return back()->with('error',$e->getMessage());
        }
    }

    public function destroy(Request $request, int $id)
    {
        if($request->isMethod('delete')){
            $client = Client::findOrFail($id);

            if(!$client->delete()){
                notify()->error('Error al eliminar cliente.', 'Error', 'topRight');
                return redirect()->route('admin.clients.index');
            }else{
                $this->deletes('Clientes', $id, 'Cliente eliminado con éxito');

                notify()->success('Cliente eliminado correctamente.', 'Éxito', 'topRight');
                return redirect()->route('admin.clients.index'); 
            }

        }else{
            return response('Invalid method',500);
        }
    }    

    private function getCondicionFiscal($info) {            
      $result = null;

      if(isset($info->datosMonotributo) && isset($info->datosMonotributo->impuesto)) {
        $impuestos = $info->datosMonotributo->impuesto;
        if(isset($info->datosMonotributo->impuesto->idImpuesto))
          $impuestos = [$info->datosMonotributo->impuesto];

        foreach ($impuestos as $key => $value) {
          $exist = CondicionFiscal::where('codigo',$value->idImpuesto)->first();          

          if($exist) {
            $result = $exist;
            break;
          }            
        }
      } elseif(isset($info->datosRegimenGeneral) && isset($info->datosRegimenGeneral->impuesto)) {            
        $impuestos = $info->datosRegimenGeneral->impuesto;
        if(isset($info->datosRegimenGeneral->impuesto->idImpuesto))
          $impuestos = [$info->datosRegimenGeneral->impuesto];     
                    
          foreach ($impuestos as $key => $value) {
            $exist = CondicionFiscal::where('codigo',$value->idImpuesto)->first();          

            if($exist) {
              $result = $exist;
              break;
            }                          
          }             
      }

      if($result)
        return $result->id;
      else 
        return null;
    }
}
