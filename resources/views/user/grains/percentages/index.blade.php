@extends('layouts.app')

@section('title', '% de rebajas según granos')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">% de rebajas según granos</h3>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center">
            <form action="{{ route('admin.grainPercentages.store') }}" class="row col-12 d-flex" method="POST">
              @csrf
              <div class="col-lg-3 py-1 d-flex flex-column pr-0">
                <label>Grano</label>
                <select class="form-control select2" name="grain_id" id="grain">
                  <option value="todos">Seleccione</option>
                  @foreach ($grains as $key => $grain)
                    <option value="{{ $key }}" @if ($key == request('grain')) selected @endif>
                      {{ $grain }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-3 py-1 d-flex flex-column pr-0">
                <label>Rubro de grano</label>
                <select class="form-control select2" name="grain_category_id" id="grain_category">
                  <option value="todos">Seleccione</option>
                  @foreach ($categories as $key => $category)
                    <option value="{{ $key }}" @if ($key == request('category')) selected @endif>
                      {{ $category }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 py-1 pr-0">
                <label>Valor hasta</label>
                <input class="form-control" min="0" name="max_value" placeholder="0" required step="0.01" type="number" />
              </div>
              <div class="col-lg-2 py-1 pr-0">
                <label>% de merma</label>
                <input class="form-control" max="100" min="-0.01" name="percentage" placeholder="0" required step="0.01" type="number" />
              </div>
              <div class="col-lg-2 pl-4 row mt-1 mb-2 ml-2 align-items-end">
                <button class="btn btn-primary btn-icon">Agregar</button>
              </div>
            </form>
          </div>
          <div class="d-flex align-items-center pt-4">
            <p class="m-0">
              Para importar los valores, debe cargar un excel que deberá tener los nombres de las columnas como en este <a href="{{ route('admin.grainPercentages.exampleImport') }}">ejemplo</a>
            </p>
            <form action="{{ route('admin.grainPercentages.import') }}" method="post" enctype="multipart/form-data" class='m-0' id='csvForm'>
              @csrf
              <input class="btn btn-light mx-2 py-0.5" hidden id="archivo" name="archivo" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                onchange="uploadCsv(this)" />
              <label id='import' class="btn btn-light h-100 ml-3 my-0">Importar</label>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <form method="GET" class='row col-12 d-flex'>
              <div class="col-3 pr-0">
                <span class="col-sm2 col-form-label m-1">Grano:</span>
                <select class="form-control select2" name="grain" id="grain_id">
                  <option selected disabled>Seleccione</option>
                  @foreach ($grains as $key => $grain)
                    <option 
                      value="{{ $key }}" 
                      @if ($key == request('grain')) selected @endif
                    >
                      {{ $grain }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-3">
                <span class="col-sm2 col-form-label m-1"> Rubro: </span>
                <select class="form-control select2" name="category" id="grain_category_id">
                  <option selected disabled>Seleccione</option>
                  @foreach ($categories as $key => $category)
                    <option 
                      value="{{ $key }}" 
                      @if ($key == request('category')) selected @endif
                    >
                      {{ $category }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-3 row pl-2 m-1 align-items-end">
                <button class="btn btn-primary btn-icon mr-2">Buscar</button>
                <a href="{{ route('admin.grainPercentages.index') }}" class="btn btn-light btn-icon">Limpiar</a>
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
                <th width="7%" class="text-left">ID</th>
                <th width="26%" class="text-left">Rubro</th>
                <th width="26%" class="text-left">Grano</th>
                <th width="26%" class="text-left">Valor hasta</th>
                <th width="26%" class="text-left">% Rebaja / Merma</th>
                <th width="15%" class="text-left">Acciones</th>
                @foreach ($grainPercentages as $pg)
                  <tr>
                    <td class="text-left">{{ $pg->id }}</td>
                    <td class="text-left">{{ $pg->getGrainCategory() }}</td>
                    <td class="text-left">{{ $pg->getGrainName() }}</td>
                    <td class="text-left">{{ $pg->max_value }}</td>
                    <td class="text-left">{{ $pg->percentage }}</td>
                    <td class="d-flex align-items-center">
                      <a href="{{ route('admin.grainPercentages.show', $pg->id) }}" class="btn btn-primary btn-sm m-1">Editar</a>
                      <form action="{{ route('admin.grainPercentages.destroy', $pg->id) }}" class='m-0' method="POST">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger btn-sm m-1" onclick="return confirm('Se eliminará el porcentaje. ¿Continuar?')">Eliminar</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </table>
              <div class="float-right mt-3">
                {{ $grainPercentages->appends($_GET)->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    const fileInput = document.getElementById('import').addEventListener('click', () => {
      document.getElementById('archivo').click();
    })

    function uploadCsv(input) {
      document.getElementById('csvForm').submit();
    }
  </script>
@endsection