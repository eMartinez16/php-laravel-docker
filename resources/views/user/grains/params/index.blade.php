@extends('layouts.app')

@section('title', 'Parámetros de granos')
@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Parámetros de granos</h3>
    </div>
    {{-- <div class="section-body">   
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <form action="{{ route('admin.params.store') }}" method="POST" class='row col-12 d-flex'>
                  @csrf
                  <div class="col-5">
                    <span class="col-sm2 col-form-label m-1">Grano:</span>
                    <select class="form-control select2" name="grain_id" id="grain">
                      <option value="todos" selected disabled>Seleccionar</option>
                      @foreach($grains as $k => $grain)
                        <option 
                          value="{{$k}}" 
                          @if(request()->query('grain') == $k) selected @endif
                        >
                          {{$grain}}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="row col-5 pl-2 align-items-end">
                    <input type="text" name="description" class="form-control" placeholder="Parámetro" required>                              
                  </div>
                  <div class="row col-2 pl-4 pb-1 align-items-end">
                    <button class="btn btn-primary btn-icon">Agregar</button>                                                            
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>          --}}
  </section>
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-striped">
                <th width="5%" class="text-left">ID</th>
                {{-- <th width="40%" class="text-left">Grano</th> --}}
                <th width="40%" class="text-left">Parámetro</th>
                <th width="15%" class="text-left">Acciones</th>                           
                @foreach ($params as $param)
                  <tr>
                    <td class="text-left">{{ $param->id }}</td>
                    {{-- <td class="text-left">{{ $param->getGrainName() }}</td>                              --}}
                    <td class="text-left">{{ $param->description }}</td>                             
                    <td class="d-flex align-items-center">
                      <a href="{{ route('admin.params.show', $param->id) }}" class="btn btn-primary btn-sm m-1">Editar</a>
                      <form action=" {{ route('admin.params.destroy', $param->id) }} " method="POST">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger btn-sm m-1" onclick="return confirm('Se eliminará la categoria {{ $param->description }}. ¿Continuar?')">Eliminar</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </table>
              <div class="float-right mt-3">
                {{ $params->appends($_GET)->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>        
  </section>
@endsection
