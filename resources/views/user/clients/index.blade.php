@extends('layouts.app')

@section('title', 'Listado de clientes')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Listado de clientes</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="GET">
                <div class="row align-items-center">
                  <div class="col-lg-2 col-12 pr-0">
                    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-lg">Agregar Cliente</a>
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Buscar:</span>
                    <input type="search" name="search" class="form-control" value="{{ $search ?? '' }}">
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Estado:</span>
                    <select class="form-control select2" name="enabled" id="enabled">
                      @foreach($enabled as $k => $estado)
                        <option 
                          value="{{ $k }}" 
                          @if ($act == $k) selected @endif
                        >
                          {{$estado}}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-2 col-12 pt-2"></div>
                  <div class="col-lg-2 col-12 d-flex align-items-center justify-content-end">
                    <div>
                      <a 
                        class="btn btn-outline-lighty float-right"
                        href="{{ route('admin.clients.index') }}" 
                      >
                        <i class="fas fa-redo" title="Vaciar filtros"></i>
                      </a>
                    </div>
                    <div class="pl-2">
                      <button type="submit" class="btn btn-primary btn-lg" title="Buscar">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
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
                <thead>
                  <th width="5%">ID</th>
                  <th width="25%">Razón social</th>
                  <th width="25%">Email</th>
                  <th width="10%">CUIT</th>
                  <th width="10%">Teléfono</th>
                  <th width="15%">Condición Fiscal</th>
                  <th width="20%">Acciones</th>
                </thead>
                <tbody>
                @if ($clients)
                  @foreach ($clients as $client)
                    <tr>
                      <td>{{$client->id}}</td>
                      <td>{{$client->business_name}}</td>
                      <td>{{$client->email}}</td>
                      @isset($client->CUIT)<td>{{$client->CUIT}}</td>@endisset
                      <td>{{$client->phone}}</td>
                      <td>
                        @if($client->fiscal_condition)
                          {{$client->fiscal_condition->nombre}}
                        @else 
                          [NO DISPONIBLE]
                        @endif
                      </td>
                      <td class="d-flex align-items-center">
                        @if($client->disabled)
                          <button class="btn btn-secondary btn-sm m-1">Ver</button>
                          <button class="btn btn-secondary btn-sm m-1">Editar</button>
                          <form action=" {{ route('admin.clients.destroy', $client->id) }} " method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm m-1" onclick="return confirm('Se eliminará el cliente {{$client->id}}. ¿Continuar?')">Eliminar</button>
                          </form>
                          <form action="{{ route('admin.clients.enabled', ['id'=>$client->id])}}" class="needs-validation" method="POST">
                            @csrf
                            <button class="btn btn-warning btn-sm m-1" onclick="return confirm('Se habilitara el cliente {{$client->id}}. ¿Continuar?')">Habilitar</button>
                          </form>
                        @else 
                          <a href="{{ route('admin.clients.show', $client->id) }}" class="btn btn-info btn-sm m-1">Ver</a>
                          <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-primary btn-sm m-1">Editar</a>
                          <form action=" {{ route('admin.clients.destroy', $client->id) }} " method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm m-1" onclick="return confirm('Se eliminará el cliente {{$client->id}}. ¿Continuar?')">Eliminar</button>
                          </form>
                          <form action="{{ route('admin.clients.disabled', ['id'=>$client->id] )}}" class="needs-validation" method="POST">
                            @csrf
                            <button class="btn btn-dark btn-sm m-1" onclick="return confirm('Se inhabilitara el cliente {{$client->id}}. ¿Continuar?')">Deshabilitar</button>
                          </form>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @endif
                </tbody>
              </table>
              <div class="float-right mt-3">
                {{ $clients->appends($_GET)->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>      
  </section>
@endsection
