@extends('layouts.app')

@section('title', 'Granos')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Granos</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-5">
          <div class="card">
            <div class="card-body">
              <form action="{{ route('admin.grains.store') }}" method="POST" class='d-flex'>
                <div class="row col-lg-12">
                  @csrf
                  <div class="col-10 pl-0">
                    <span class="col-sm2 col-form-label m-1">Grano:</span>
                    <input type="text" name="name" class="form-control" placeholder="Nombre de grano">
                  </div>
                  <div class="row col-2 pb-1 pr-0 align-items-end">
                    <button class="btn btn-primary btn-icon">Agregar</button>
                  </div>
                </div>
              </form>
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
                <th width="80%" class="text-left">Grano</th>
                <th width="15%" class="text-left">Acciones</th>
                @foreach ($grains as $grain)
                  <tr>
                    <td class="text-left">{{ $grain->id }}</td>
                    <td class="text-left">{{ $grain->name }}</td>
                    <td class="d-flex align-items-center">
                      <a href="{{ route('admin.grains.show', $grain->id) }}" class="btn btn-primary btn-sm m-1">Editar</a>
                      <form action=" {{ route('admin.grains.destroy', $grain->id) }} " method="POST">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger btn-sm m-1" onclick="return confirm('Se eliminará la categoria {{ $grain->name }}. ¿Continuar?')">Eliminar</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </table>
              <div class="float-right mt-3">
                {{ $grains->appends($_GET)->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
