<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\Grain;
use App\Models\GrainParams;
use Illuminate\Http\Request;

class GrainParamsController extends Controller
{
    use LogsTrait;
    private $grains;

    public function __construct()
    {
        $this->grains = Grain::pluck('name', 'id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = GrainParams::paginate(20);

        $grains = $this->grains;

        return view('user.grains.params.index', compact('params','grains'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $req)
    {
        if($req->isMethod('POST')){   
            if($req->grain_id == 'todos'){
                notify()->error('Debe seleccionar un tipo de grano', 'Error');
                return redirect()->route('admin.params.index');  
            }    
            $newParam = new GrainParams();            
            if(!$newParam->create($req->all())){
                notify()->error('Error al crear parámetro', 'Error');
            }

            $this->creates('Parámetro de grano', $newParam::latest('id')->first()->id, 'Creado');
            notify()->success('Parámetro creado correctamente', 'Éxito');     
            return redirect()->route('admin.params.index');  
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req)
    {
        $grain_param = GrainParams::findOrFail($req->id);
        $grains = $this->grains;
        return view('user.grains.params.edit', compact('grain_param', 'grains'));

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grain  $grain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        if($req->id){            
            $param = GrainParams::findOrFail($req->id);

            if(!$param->update($req->all())){
                notify()->error('Error al actualizar parámetro', 'Error');
            }

            $this->updates('Parámetro de grano', $param->id, 'Actualizado');
            notify()->success('Parámetro actualizado correctamente', 'Éxito');     
            return redirect()->route('admin.params.index');  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grain  $grain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        if($req->isMethod('delete')){
            $params = GrainParams::findOrFail($req->id);

            if(!$params->delete()){
                notify()->error('Error al eliminar parámetro.', 'Error');
            }

            $this->deletes('Parámetro de grano', $req->id, 'Eliminado');
            notify()->success('Parámetro eliminado correctamente', 'Éxito');
            return redirect()->route('admin.params.index');  
        }
    }
}
