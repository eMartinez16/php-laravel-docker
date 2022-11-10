@extends('layouts.app')

@section('title', 'Listado de usuarios')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Listado de usuarios</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="GET">
                <div class="row align-items-center">
                  <div class="col-lg-2 col-12 pr-0 d-flex flex-wrap justify-content-between">
                    <a href="{{ route('admin.usuario.create') }}" class="btn btn-primary btn-lg">Agregar Usuario</a>
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Buscar:</span>
                    <input type="search" name="search" class="form-control" value="{{$search ?? ''}}">
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Estado:</span>
                    <select class="form-control select2" name="estados" id="estados">
                      @foreach($estados_var as $k => $estado)
                        <option 
                          value="{{ $k }}" 
                          @if ($est === $k) selected @endif
                        >
                          {{ $estado }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-2 col-12"></div>
                  <div class="col-lg-2 col-12 d-flex align-items-center justify-content-end">
                    <div>
                      <a 
                        class="btn btn-outline-lighty float-right"
                        href="{{ route('admin.usuario.index') }}" 
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
                <th width="5%">ID</th>
                <th width="10%">Nombre</th>
                <th width="10%">Apellido</th>
                <th width="30%">Email</th>
                <th width="10%">DNI</th>
                <th width="10%">Teléfono</th>
                <th width="10%">Activar</th>
                <th width="15%">Acciones</th>
                @foreach ($users as $user)
                  <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->apellido }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->dni }}</td>
                    <td>{{ $user->telefono }}</td>
                    <td>
                      @if ($user->estado == 'no_activo' && auth()->user()->role == 'administrador')
                        <form action=" {{ route('admin.usuario.activar', $user->id) }} " method="POST">
                          @csrf
                          @method('PUT')
                          <button class="btn btn-success btn-sm" onclick="return confirm('Se aceptará el usuario {{$user->nombre}}. ¿Continuar?')"><i class="fas fa-check"></i></button>
                        </form>
                      @endif
                      @if ($user->estado == 'activo')
                        {{ 'Activo' }}
                      @endif
                    </td>
                    <td class="d-flex align-items-center">
                      <a href="{{ route('admin.usuario.show', $user->id) }}" class="btn btn-info btn-sm">Ver</a>
                      <a href="{{ route('admin.usuario.edit', $user->id) }}" class="btn btn-primary btn-sm ml-2 mr-2">Editar</a>
                      @if($user->estado == 'activo')
                        <form action=" {{ route('admin.usuario.desactivar', $user->id) }} " method="POST">
                          @csrf
                          @method('PUT')
                          <button class="btn btn-danger btn-sm" onclick="return confirm('Se desactivara el usuario {{$user->nombre}}. ¿Continuar?')">Desactivar</button>
                        </form>
                      @endif
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
