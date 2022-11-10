@extends('layouts.app')

@section('title', 'Editar grano')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Editar grano</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="post" action=" {{ route('admin.grains.update', $grain->id) }} ">
                @csrf
                @method('PATCH')
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label>Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{$grain->name}}" required>
                  </div>
                </div>
                <div class="">
                  <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
                  <a href="{{ route('admin.grains.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Cancelar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>    
@endsection
