<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\CategoriesGrains;
use App\Models\Grain;
use App\Models\GrainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GrainCategoryController extends Controller
{   
    use LogsTrait;
    private Collection $grains;

    public function __construct()
    {
        $this->grains = Grain::pluck('name', 'id');
    }

    public function index()
    {
        $grains          = $this->grains;
        $grainCategories = GrainCategory::paginate(20);

        return view('user.grains.categories.index', compact('grains', 'grainCategories'));
    }

    /** 
     * Function to create the relation between `rubro` and `grano`
     * @param Request $req
     */
    public function relate(Request $req)
    {
        if ($req->isMethod('POST')) {   
            if ($req->grain_id == 'todos') {
                notify()->error('Debe seleccionar un tipo de grano', 'Error');
                return redirect()->route('admin.categories.index');  
            }   
            if ($req->grain_category_id == 'todos') {
                notify()->error('Debe seleccionar un rubro de grano', 'Error');
                return redirect()->route('admin.categories.index');  
            }
            
            $newCategoryGrain = new CategoriesGrains();

            if (!$newCategoryGrain->create($req->all())) {
                notify()->error('Error al crear la relación entre grano y rubro', 'Error');
            }

            $this->general('Rubros/grano', $newCategoryGrain::latest('id')->first()->id, 'Grano y rubro relacionados', 'Relacionado');
            notify()->success('Relación creada correctamente', 'Éxito');     
            return redirect()->route('admin.categories.index');  
        }
    }

    /**
     * Stores a new `rubro`
     * @param Request $req
     */
    public function store(Request $req)
    {
        if($req->isMethod('POST')){               
            $newCategory = new GrainCategory();
            if (!$newCategory->create($req->all())) {
                notify()->error('Error al crear rubro', 'Error');
            }

            $this->creates('Rubro de grano', $newCategory::latest('id')->first()->id, 'Creado');
            notify()->success('Rubro creado correctamente', 'Éxito');     
            return redirect()->route('admin.categories.index');  
        }
    }

    public function show(Request $req)
    {
        $grain_category = GrainCategory::findOrFail($req->id);

        $grains = $this->grains;

        return view('user.grains.categories.edit', compact('grain_category', 'grains'));

    }

    public function update(Request $req)
    {
        if($req->id){            
            $category = GrainCategory::findOrFail($req->id);

            if (!$category->update($req->all())) {
                notify()->error('Error al actualizar rubro', 'Error');
            }

            $this->updates('Rubro de grano', $category->id, 'Actualizado');
            notify()->success('Rubro actualizado correctamente', 'Éxito');     
            return redirect()->route('admin.categories.index');  
        }
    }

    public function destroy(Request $req)
    {
        if ($req->isMethod('delete')) {
            $category = GrainCategory::findOrFail($req->id);

            if (!$category->delete()) {
                notify()->error('Error al eliminar rubro.', 'Error');
            }            

            $this->deletes('Rubro de grano', $req->id, 'Eliminado');
            notify()->success('Rubro eliminado correctamente', 'Éxito');
            return redirect()->route('admin.categories.index');  
        }
    }
}
