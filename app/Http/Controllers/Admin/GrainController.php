<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\Grain;
use Illuminate\Http\Request;

class GrainController extends Controller
{
    use LogsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grains = Grain::paginate(20);

        return view('user.grains.index', compact('grains'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $req)
    {
        if($req->isMethod('POST')){
            
            $newGrain = new Grain();
            if(!$newGrain->create($req->all())){
                notify()->error('Error al crear grano', 'Error');     
            }

            $this->creates('Grano', $newGrain::latest('id')->first()->id, 'Grano creado');
            notify()->success('Grano creado correctamente', 'Éxito');     
            return redirect()->route('admin.grains.index');  
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req)
    {
        return view('user.grains.edit', ['grain' => Grain::findOrFail($req->id) ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $req, Grain $grain)
    {
        if($req->id){
            $grain = Grain::findOrFail($req->id);

            if(!$grain->update(['name' => $req->name])){
                notify()->error('Error al actualizar grano', 'Error');
            }

            $this->updates('Grano', $grain->id, 'Grano actualizado');
            notify()->success('Grano actualizado correctamente', 'Éxito');     
            return redirect()->route('admin.grains.index');  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        if($req->isMethod('delete')){
            $grain = Grain::findOrFail($req->id);

            if(!$grain->delete()){
                notify()->error('Error al eliminar grano.', 'Error');
            }

            $this->deletes('Grano', $req->id, 'Grano eliminado');
            notify()->success('Grano eliminado correctamente', 'Éxito');
            return redirect()->route('admin.grains.index');  
        }
    }
}
