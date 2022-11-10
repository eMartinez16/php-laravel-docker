@extends('layouts.app')

@section('title', 'Reporte de cámara')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Reporte de cámara</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-6 col-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between">
                <p class="m-0">
                  Importar valores a partir del txt emitido por la cámara. 
                </p>
                <form action="{{ route('admin.cameraReport.import') }}" method="post" enctype="multipart/form-data" class='m-0' id='txtForm'>
                  @csrf
                  <input class="btn btn-light mx-2 py-0.5" hidden id="archivo" name="archivo" type="file" accept=".txt"
                    onchange="importTxt(this)" />
                  <label id='import' class="btn btn-light h-100 ml-3 my-0">Importar</label>
                </form>
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
    const importTxt = (input) => {
      document.getElementById('txtForm').submit();
    }
  </script>
@endsection