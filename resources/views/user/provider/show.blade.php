@extends('layouts.app')

@section('title', 'Proveedor')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Editar proveedor</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="post" action=" {{ route('admin.provider.update', $provider->id) }} ">
                @csrf
                @method('PATCH')
                <div class="row">
                  <div class="col-lg-5">
                    <label>Razón social</label>
                    <input 
                      class="form-control"
                      name="business_name"
                      required
                      type="text"
                      value="{{ $provider->business_name }}"
                    />
                  </div>
                  <div class="col-lg-5">
                    <label>CUIT</label>
                    <input 
                      class="form-control"
                      min="0"
                      name="cuit"
                      required
                      type="text"
                      value="{{ $provider->cuit }}"
                    />
                  </div>
                  <div class="col-lg-5">
                    <label>Contacto</label>
                    <input 
                      class="form-control"
                      name="contact"
                      type="text"
                      value="{{ $provider->contact }}"
                    />
                  </div>
                  <div class="col-lg-5">
                    <label>Descripión</label>
                    <input
                      class="form-control"
                      name="description"
                      type="text"
                      value="{{ $provider->description }}"
                    />
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-4 mb-0">
                    <label for="cost">Costo</label>
                    <select class="form-control select2" name="cost">
                      @foreach($costs as $key => $cost)                                      
                        <option 
                          value="{{ $key }}" 
                        >
                          {{$cost}}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
                  <a 
                    class="btn btn-light ml-1 edit-cancel-margin margin-left-5"
                    href="{{ route('admin.provider.index') }}" 
                  >
                    Cancelar
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
