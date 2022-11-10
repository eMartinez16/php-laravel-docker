@extends('layouts.app')

@section('title', 'Categorías de granos')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Rubros de granos</h3>
    </div>        
    <div class="section-body">   
      <div class="row">
        <div class="col-lg-7">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <form action="{{ route('admin.categories.relate') }}" method="POST" class="row col-12 d-flex">
                  @csrf
                  <div class="col-5">
                    <span class="col-sm2 col-form-label m-1">Grano:</span>
                    <select class="form-control select2" name="grain_id" id="grain_id">                              
                      <option value="todos" selected disabled>Seleccionar</option>
                      @foreach($grains as $k => $grain)
                        <option 
                          value="{{ $k }}" 
                          @if(request()->query('grain') == $k) selected @endif
                        >
                          {{$grain}}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-5">
                    <span class="col-sm2 col-form-label m-1">Rubro:</span>
                    <select class="form-control select2" name="grain_category_id" id="grain_category_id">                              
                      <option value="todos" selected disabled>Seleccionar</option>
                      @foreach($grainCategories as $category)
                        <option 
                          value="{{ $category->id }}" 
                          @if(request()->query('grain_category_id') == $category->id) selected @endif
                        >
                          {{ $category->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="row col-2 pl-4 pb-1 align-items-end">
                    <button class="btn btn-primary btn-icon">Relacionar</button>                                                            
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="row col-12 d-flex justify-content-center">
                  @csrf
                  <div class="col-10 pl-2 align-items-end">
                    <span class="col-sm2 col-form-label m-1"></span>
                    <input type="text" name="name" class="form-control" placeholder="Rubro" required>                              
                  </div>
                  <div class="row col-2 pl-2 pb-1 align-items-end">
                    <button class="btn btn-primary btn-icon">Agregar</button>                                                            
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>         
  </section>
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-striped">
                <th width="5%" class="text-left">ID</th>
                <th width="40%" class="text-left">Rubro</th>
                <th width="40%" class="text-left">Granos</th>
                <th width="15%" class="text-left">Acciones</th>            
                @foreach ($grainCategories as $category)
                  <tr>
                    <td class="text-left">{{ $category->id }}</td>    
                    <td class="text-left">{{ $category->name }}</td>                             
                    <td class="text-left">{{ implode(', ', $category->getGrainNames()) }}</td>                             
                    <td class="d-flex align-items-center">
                      <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-primary btn-sm m-1">Editar</a>
                      <form action=" {{ route('admin.categories.destroy', $category->id) }} " method="POST">
                        @csrf
                        @method('delete')
                        <button 
                          class="btn btn-danger btn-sm m-1" 
                          onclick="return confirm('Se eliminará la categoria {{ $category->name }}. ¿Continuar?')"
                        >
                          Eliminar
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </table>
              <div class="float-right mt-3">
                {{ $grainCategories->appends($_GET)->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>        
  </section>
@endsection
