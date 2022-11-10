@extends('layouts.app')

@section('title', 'Centro de costos')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Centro de costos</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-6">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <form action="{{ route('admin.cost-centers.store') }}" method="POST" class='row col-12 d-flex'>
                  @csrf
                  <div class="col-lg-5 col-12 py-1 pr-0">
                    <span class="col-sm2 col-form-label m-1">Descripción:</span>
                    <input 
                      class="form-control" 
                      name="description" 
                      placeholder="Descripción" 
                      required
                      type="text" 
                    />
                  </div>
                  <div class="col-lg-5 col-12 align-items-center py-1 pr-0">
                    <span class="col-sm2 col-form-label m-1">Tipo:</span>
                    <select 
                      class="form-control select2"
                      id="type"
                      name="type"
                      required
                    >
                      <option value selected disabled>Seleccione</option>
                      @foreach($types as $k => $type)
                        <option value="{{ $type }}">
                          {{ $k }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-2 py-2 d-flex align-items-end">
                    <button class="btn btn-primary btn-icon">Agregar</button>                                                            
                  </div>
                </form>
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
                <th width="50%" class="text-left">Descripción</th>
                <th width="30%" class="text-left">Tipo</th>
                <th width="15%" class="text-left">Tipo</th>
                @foreach ($costCenters as $costCenter)
                  <tr>
                    <td class="text-left">{{ $costCenter->id }}</td>
                    <td class="text-left">{{ $costCenter->description }}</td>
                    <td class="text-left">{{ ucfirst(trans($costCenter->type)) }}</td>
                    <td class="d-flex align-items-center">
                      <a href="{{ route('admin.cost-centers.show', $costCenter->id) }}" class="btn btn-primary btn-sm m-1">Editar</a>
                      <form action=" {{ route('admin.cost-centers.destroy', $costCenter->id) }} " method="POST">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger btn-sm m-1" onclick="return confirm('Se eliminará el centro de costo. ¿Continuar?')">Eliminar</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
