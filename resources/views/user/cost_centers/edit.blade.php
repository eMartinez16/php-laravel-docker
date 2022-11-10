@extends('layouts.app')

@section('title', 'Editar centro de costo')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Editar centro de costo</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="post" action=" {{ route('admin.cost-centers.update', $costCenter->id) }} ">
                @csrf
                @method('PATCH')
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label for="description">Descripción: </label>
                    <input 
                      class="form-control" 
                      id="description" 
                      name="description" 
                      required
                      type="text" 
                      value="{{ $costCenter->description }}" 
                    />
                  </div>
                  <div class="form-group col-sm-6">
                    <label for="type">Tipo: </label>
                      <select class="form-control select2" name="type" id="type">
                      @foreach($types as $k => $type)                                      
                        <option 
                          value="{{ $type }}" 
                          @if($type == $costCenter->type) selected @endif
                        >
                          {{ $k }}
                        </option>
                      @endforeach
                      </select>
                    </div>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
                  <a 
                    class="btn btn-light ml-1 edit-cancel-margin margin-left-5"
                    href="{{ route('admin.cost-centers.index') }}" 
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
