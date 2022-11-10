@extends('layouts.app')

@section('title', 'Editar cliente')
@section('content')
  <section class="section">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <div class="section-header">
      <h3 class="page__heading">Agregar Usuario</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="POST" action=" {{ route('admin.clients.update', $client->id) }} ">
                <div class="row">
                  @csrf
                  @method('PATCH')
                  <div class="form-group col-sm-6">
                    <label>Responsable</label>
                    <input type="text" name="liable" id="liable" class="form-control" value="{{ $client->liable }}" required>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>Razón social</label>
                    <input type="te xt" name="business_name" id="business_name" class="form-control" value="{{ $client->business_name }}" required>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $client->email }}" required>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>CUIT</label>
                    <input type="text" name="CUIT" id="CUIT" class="form-control" value="{{ $client->CUIT }}" required>
                  </div>
                  <div class="form-group col-sm-4">
                    <label>Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ $client->phone }}" required>
                  </div>
                  <div class="form-group col-sm-4">
                    <label>Domicilio</label>
                    <input type="text" name="residence" id="residence" class="form-control" value="{{ $client->residence }}" required>
                  </div>
                  <div class="form-group col-sm-4">
                    <label>Localidad</label>
                    <input type="text" name="location" id="location" class="form-control" value="{{ $client->location }}" required>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>Categoria</label>
                    <select class="form-control select2" name="category_id" id="category_id" empty="Selecciona una Categoria" required>
                        <option value="" hidden>Selecciona una opción</option>
                      @foreach ($categories as $k => $category)
                        <option value="{{ $k }}" @if (!is_null($client->category) && $client->category->name == $category)
                                            selected @endif>
                          {{ $category }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>Condición de pago</label>
                    <select class="form-control select2" name="payment_condition" id="payment_condition" empty="Selecciona una condicion de pago" required>
                      @foreach ($payment_condition as $key => $value)
                        @if ($client->payment_condition != null && $client->payment_condition['reference'] == $key)
                          <option value="{{ $key }}" selected>{{ $value }}</option>
                        @else
                          <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary" tabindex="5">Guardar cambios</button>
                  <a href="{{ route('admin.clients.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Cancelar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>

@endsection
