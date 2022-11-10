@extends('layouts.app')

@section('title', 'Parámetros de granos')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Editar parámetros de granos</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="post" action=" {{ route('admin.params.update', $grain_param->id) }} ">
                <div class="row">
                  @csrf
                  @method('PATCH')
                  <div class="form-group col-sm-12">
                    <label for="grain_id">Grano</label>
                      <select class="form-control select2" name="grain_id" id="grain">

                        @foreach($grains as $k => $grain)                                      
                          <option value="{{$k}}" @if($k == $grain_param->grain_id) selected @endif>{{$grain}} </option>
                        @endforeach
                      </select>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Parámetro</label>
                    <input type="text" name="description" class="form-control" value="{{$grain_param->description}}" required>
                  </div>
                </div>
                <div class="">
                  <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
                  <a href="{{ route('admin.params.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Cancelar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>    
@endsection
