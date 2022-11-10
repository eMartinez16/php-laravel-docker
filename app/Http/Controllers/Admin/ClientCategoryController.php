<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientCategoryRequest;
use App\Models\ClientCategory;
use Illuminate\Http\Request;
use App\Http\Traits\LogsTrait;

class ClientCategoryController extends Controller
{
    use LogsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ClientCategory::orderBy('id', 'desc')
                                     ->paginate(20);

        return view('user.client_category.index', compact('categories'));
    }

    public function edit($id)
    {
        $category = ClientCategory::findOrFail($id);

        return view('user.client_category.edit', [
            'category' => $category
        ]);
    }

    public function store(ClientCategoryRequest $request){ 
        if($request->isMethod('post')){
            $category = new ClientCategory();

            if($category->create($request->validated())){
                $this->creates('Categorias Clientes', ClientCategory::latest('id')->first()->id, 'Categoria de cliente creada con éxito');

                notify()->success('Categoria creada con éxito.', 'Éxito', 'topRight');
            }else{
                notify()->error('Error al crear categoria', 'Error');            
            }
            return back();
        }else{
            return response('Invalid method',500);
        }
    }

    public function update(ClientCategoryRequest $request, $id){
        if($request->isMethod('patch')){
            $category = ClientCategory::find($id);
            if(!is_null($category)){
                if($category->update($request->validated())){
                    $this->updates('Categorias Clientes', $category->id, 'Categoria de cliente actualizada con éxito');

                    notify()->success('Categoria actualizada correctamente', 'Éxito', 'topRight');
                }else{
                    notify()->error('Error al actualizar categoria.', 'Error', 'topRight');
                }
            }else{
                notify()->error('No se encontró la categoria.', 'Error', 'topRight');
            }
        }else{
            return response('Invalid method.',500);
        }
        return redirect()->route('admin.client_category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->isMethod('delete')){
            $category = ClientCategory::find($id);

            if(!is_null($category)){
                if ($category->clients->count()>0) {
                    return back()->with('error',"No puede borrar una categoría con clientes asignados");
                } else {
                    if($category->delete()){
                        $this->deletes('Categorias Clientes', $id, 'Categoria de cliente eliminada con éxito');
    
                        notify()->success('Categoria eliminada correctamente', 'Éxito', 'topRight');
                    }else{
                        notify()->error('Error al eliminar categoria.', 'Error', 'topRight');     
                    }
                }
            }else{
                notify()->error('No se encontró la categoria.', 'Error', 'topRight');
            }
            return back();
        }else{
            return response('Invalid method', 500);
        }
    }
}
