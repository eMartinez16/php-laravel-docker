<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\ClientCategory;
use App\Models\Rate;
use App\Models\RateClientCategory;
use Illuminate\Http\Request;

class RateController extends Controller
{
    use LogsTrait;
    private $categories;

    public function __construct()
    {
        $this->categories = ClientCategory::pluck('name', 'id');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->categories;

        return view('user.rates.index', compact('categories'));
    }

    public function history(){        
        return view('user.rates.history',[
            'ratesCategory'=> RateClientCategory::orderBy('id','DESC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->isMethod('POST')){            
            if($request->categories){
                foreach ($request->categories as $key => $percentage) {
                    //Porcentaje * total de tarifa / 100
                    if($percentage > 100 || $percentage < 0){
                        notify()->error('Ingrese un porcentaje válido (0-100)', 'Error');
                        return redirect()->route('admin.rates.index');  
                    }

                    $newRate = new Rate();

                    if(!$newRate->create(['value'=> $request->value])){
                        notify()->error('Error al crear tarifa', 'Error');
                    }
                    
                    $newRateCategory = new RateClientCategory();
                    $newRateCategory->total              = (($percentage ?? 100) * $request->value)/100;
                    $newRateCategory->percentage         = $percentage ?? 100;
                    $newRateCategory->rate_id            = $newRate::latest('id')->first()->id;
                    $newRateCategory->client_category_id = $key;
                    if(!$newRateCategory->save()){
                        notify()->error('Error al almacenar porcentaje, categoria: '.$key, 'Error');
                    }
                }
            }
            
            $this->creates('Tarifa', $newRateCategory::latest('id')->first()->id, 'Creado');
            notify()->success('Tarifa creada correctamente', 'Éxito');

            return redirect()->route('admin.rates.index');  
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('user.rates.show',[
            'rate' => Rate::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->id){
            $rate = Rate::findOrFail($request->id);

            if(!$rate->update($request->all())){
                notify()->error('Error al actualizar tarifa', 'Error');
            }

            $this->updates('Tarifa', $rate->id, 'Actualizado');
            notify()->success('Tarifa actualizada correctamente', 'Éxito');
        }
        return redirect()->route('admin.rates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->id){
            $rate = Rate::findOrFail($request->id);

            if(!$rate->delete()){
                notify()->error('Error al eliminar tarifa', 'Error');
            } 

            $this->deletes('Tarifa', $request->id, 'Eliminado');
            notify()->success('Tarifa eliminada correctamente', 'Éxito');

            return redirect()->route('admin.rates.index');
        }
    }
}
