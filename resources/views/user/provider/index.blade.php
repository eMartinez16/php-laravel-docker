@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Proveedores</h3>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('admin.provider.store') }}" method="POST">
            <div class="row align-items-center">
              @csrf
              <div class="col-lg-2 py-1 d-flex flex-column">
                <label>Razón social</label>
                <input class="form-control"  name="business_name"  required type="text" />
              </div>
              <div class="col-lg-2 py-1 d-flex flex-column">
                <label>Centro de costo</label>
                <select class="form-control select2" name="cost_center_id">
                  <option value="todos" disabled>Seleccione</option>
                  @foreach ($costs as $key => $cost)
                    <option value="{{ $key }}">
                      {{ $cost }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 py-1">
                <label>CUIT</label>
                <input class="form-control" name="cuit" required type="number" />
              </div>
              <div class="col-lg-2 py-1">
                <label>Contacto</label>
                <input class="form-control"  name="contact" type="text" />
              </div>
              <div class="col-lg-2 py-1">
                <label>Descripcion</label>
                <input class="form-control"  name="description"  type="text" />
              </div>
              <div class="col-lg-2 py-1">
                <button class="btn btn-primary btn-icon">Agregar</button>
              </div>
            </div>
          </form>
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
                <th width="7%" class="text-left">ID</th>
                <th width="20%" class="text-left">Razón social</th>
                <th width="10%" class="text-left">CUIT</th>
                <th width="20%" class="text-left">Contacto</th>
                <th width="20%" class="text-left">Descripción</th>
                <th width="15%" class="text-left">Centro de costo</th>
                <th width="25%" class="text-left">Acciones</th>
                @foreach ($providers as $provider)
                  <tr>
                    <td class="text-left">{{ $provider->id }}</td>
                    <td class="text-left">{{ $provider->business_name }}</td>
                    <td class="text-left">{{ $provider->cuit }}</td>
                    <td class="text-left">{{ $provider->contact }}</td>
                    <td class="text-left">{{ $provider->description }}</td>
                    <td class="text-left">{{$provider->cost}}</td>
                    <td>
                      <div class="d-flex flex-row">
                        <a href="{{ route('admin.provider.show', $provider->id) }}" class="btn btn-primary btn-sm m-1">Editar</a>
                        <form action="{{ route('admin.provider.destroy', $provider->id) }}" class='m-0' method="POST">
                          @csrf
                          @method('delete')
                          <button class="btn btn-danger btn-sm m-1" onclick="return confirm('Se eliminará el proveedor. ¿Continuar?')">Eliminar</button>
                        </form>
                      </div>
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