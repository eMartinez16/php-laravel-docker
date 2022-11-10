@extends('layouts.app')

@section('title', 'Listado de categorias de clientes')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Listado de categorias de cliente</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <form action="{{ route('admin.client_category.store') }}" method="POST" class='d-flex'>
                <div class="row">
                    @csrf
                  <div class="col-10">
                    <span class="col-sm2 col-form-label m-1"> Crear categoria: </span>
                    <input type="text" name="name" class="form-control" placeholder="Nombre de la categoria">
                  </div>
                  <div class="row col-2 pl-4 pb-1 align-items-end">
                    <button type="submit" class="btn btn-primary">Agregar</button>
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
                  <th width="80%">Nombre</th>
                  <th width="15%">Acciones</th>
                </thead>
                <tbody>
                  @if ($categories)
                    @foreach ($categories as $category)
                      <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td class="d-flex align-items-center">
                          <a href="{{ route('admin.client_category.edit', $category->id) }}" class="btn btn-primary btn-sm m-1">Editar</a>
                          <form action=" {{ route('admin.client_category.destroy', $category->id) }} " method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm m-1" onclick="return confirm('Se eliminará la categoria {{ $category->nombre }}. ¿Continuar?')">Eliminar</button>
                          </form>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
                <div class="float-right mt-3">
                  {{ $categories->appends($_GET)->links('pagination::bootstrap-4') }}
                </div>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
