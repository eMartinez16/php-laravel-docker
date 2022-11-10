<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\CostCenter;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    use LogsTrait;
    private $costs;

    public function __construct()
    {
        $this->costs = CostCenter::pluck('description', 'id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::all();
        $costs = $this->costs;
        return view('user.provider.index', compact('providers', 'costs'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->isMethod('post')){       
            /**
             * ver esta parte, al utilizar $request->all(), se puede guardar el token, no es muy seguro, recomiendo hacer lo que está escrito abajo
             */
            // $data = $request->all();
            // unset($data['_token']);         
            if(!Provider::create($request->all())) {

                notify()->error('Error al crear proveedor', 'Error');
                return redirect()->route('admin.provider.index'); 
            }

            $this->creates('Proveedor', Provider::latest('id')->first()->id, 'Creado');
            notify()->success('Proveedor creado correctamente', 'Éxito');
            return redirect()->route('admin.provider.index'); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $provider = Provider::findOrFail($id);
        $costs    = $this->costs;

        return view('user.provider.show', compact('provider', 'costs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->isMethod('patch')){
            if($id = $request->id){
                $provider = Provider::find($id);

                if(!$provider->update($request->all())){

                    notify()->error('Error al actualizar proveedor', 'Error');
                    return redirect()->route('admin.provider.index');  
                }

                $this->updates('Proveedor', $provider->id, 'Actualizado');
                notify()->success('Proveedor actualizado correctamente', 'Éxito');
                return redirect()->route('admin.provider.index'); 
            }

            notify()->error('No existe el proveedor.', 'Error');

            return redirect()->route('admin.provider.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if(request()->isMethod('delete')) {
            $provider = Provider::find($id);

            if(!is_null($provider)) {            
                if(!$provider->delete()){
                    
                    notify()->error('Error al eliminar proveedor', 'Error');
                    return redirect()->route('admin.provider.index');  
                }
                
                $this->deletes('Proveedor', $id, 'Eliminado');
                notify()->success('Proveedor eliminado correctamente', 'Éxito');
                return redirect()->route('admin.provider.index');  
            }
        
            notify()->error('No existe el proveedor.', 'Error');

            return redirect()->route('admin.provider.index');  
        }
    }
}
