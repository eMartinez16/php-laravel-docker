@extends('layouts.app')

@section('title', '% de rebajas según granos')
@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Editar % de rebajas según granos</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="post" action=" {{ route('admin.grainPercentages.update', $grainPercentage->id) }} ">
                <div class="row">
                  @csrf
                  @method('PATCH')
                  <div class="form-group col-lg-3 mb-0">
                    <label for="grain_id">Grano</label>
                    <select class="form-control select2" name="grain_id" id="grain" disabled>
                      @foreach($grains as $key => $grain)                                      
                        <option 
                          value="{{ $key }}" 
                          @if($key == $grainPercentage->grain_id) selected @endif
                        >
                          {{$grain}}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-lg-3 mb-0">
                    <label for="grain_id">Rubro de grano</label>
                    <select class="form-control select2" name="grain_category_id" id="grain_category" disabled>
                      @foreach($categories as $key => $category)                                      
                        <option 
                          value="{{ $key }}" 
                          @if($key == $grainPercentage->grain_id) selected @endif
                        >
                          {{$category}}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-3">
                    <label>Valor hasta</label>
                    <input 
                      class="form-control" 
                      min="0"
                      name="max_value" 
                      placeholder="0" 
                      required
                      step="0.01"
                      type="number" 
                      value="{{ $grainPercentage->max_value }}" 
                    />
                  </div>
                  <div class="col-lg-3">
                    <label>% de rebaja</label>
                    <input 
                      class="form-control" 
                      max="100"
                      min="-0.01"
                      name="percentage" 
                      placeholder="0" 
                      required
                      step="0.01"
                      type="number" 
                      value="{{ $grainPercentage->percentage }}" 
                    />
                  </div>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
                  <a 
                    class="btn btn-light ml-1 edit-cancel-margin margin-left-5"
                    href="{{ route('admin.grainPercentages.index') }}" 
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
