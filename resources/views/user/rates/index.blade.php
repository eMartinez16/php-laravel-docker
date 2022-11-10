@extends('layouts.app')

@section('title', 'Tarifas')
@section('content')

  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Tarifas</h3>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="card col-xl-4 col-lg-6 col-md-8 col-sm-12">
          <div class="card-body">
            <form action="{{ route('admin.rates.store') }}" method="POST">
              @csrf
              <div class="row justify-content-between align-items-center">
                <div class="col-3 m-0">
                  <label for="value">Valor de tarifa</label>
                </div>
                <div class="col-5">
                  <input type="number" name="value" class="form-control" required>
                </div>
                <div class="col-3 pr-0">
                  <a href="{{ route('admin.rates.history') }}" class='btn btn-primary btn-icon h-100'>Historial</a>
                </div>
                <div class="col-12 col-lg-12 d-flex flex-column my-4">
                  <div class="d-flex flex-row justify-content-between w-100">
                    <div class="w-90">
                      <h6>Categorias</h6>
                    </div>
                    <div class="pl-3 w-40">
                      <h6>% a pagar</h6>
                    </div>
                  </div>
                  @foreach ($categories as $key => $category)
                    <div class="d-flex flex-row align-items-center justify-content-between w-100 py-1">
                      <div class="w-90">
                        <label class="mb-0">{{ $category }}</label>
                      </div>
                      <div class="pl-3 w-40">
                        <input type="hidden" name="categories[{{ $key }}]">
                        <input 
                          class="form-control"
                          max="100"
                          min="0"
                          step="0.01"
                          name="categories[{{ $key }}]"
                          type="number"
                        />
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
              <button class="btn btn-primary btn-icon">Guardar</button>
            </form>
          </div>
        </div>
      </div>
  </section>


@endsection
