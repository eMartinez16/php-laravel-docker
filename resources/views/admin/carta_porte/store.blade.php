@extends('layouts.app')

@section('title', 'Agregar Carta de Porte')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Agregar Carta de Porte</h3>
        </div>
        <form method="POST" action=" {{ route('admin.carta_porte.store') }} " enctype="multipart/form-data">
          <div class="section-body">
            <div class="section-body">
                    @csrf
                    <div class="card">
                      <div class="card-body">
                        <label for="cabecera">Cabecera</label>      
                        <div class="row">
                                  <div class="form-group col-sm-6">
                                    <label for="tipo">Tipo de carta de porte</label>
                                    <input type="text" class="form-control" name="tipo" value="{{(explode('/', url()->current())[6])}}" disabled> 
                                    <input type="hidden" class="form-control" name="tipo" value="{{(explode('/', url()->current())[6])}}" > 
                                  </div>
                                  <div class="form-group col-sm-6">
                                      <label>Estado (Por defecto se guarda estado manual)</label>
                                      <input type="hidden" name="estado" class="form-control" value="M"  title="Manual">
                                      <input type="text" name="estado" class="form-control" value="M"  title="Manual" disabled>
                                  </div>
                                                        
                                    <div class="form-group col-sm-6">
                                        <label>CTG</label>
                                        <input type="text" name="ctg" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Sucursal</label>
                                        <input type="text" name="sucursal" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Fecha emisión</label>
                                        <input type="datetime-local" step="1" name="fecha_emision" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Fecha inicio</label>
                                        <input type="datetime-local" step="1" name="fecha_inicio_estado" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Fecha vencimiento</label>
                                        <input type="datetime-local" step="1" name="fecha_vencimiento" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                      <label>Observaciones</label>
                                        <textarea 
                                          class="form-control"
                                          name="observaciones"
                                          rows="10"
                                          cols='50'
                                          style="height: 8em !important;"
                                        ></textarea> 
                                  </div>                              
                        </div>
                    </div>
            </div>
            <div class="section-body">
              <div class="card">
                <div class="card-body">
                      <label for="cabecera">Origen</label>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>CUIT</label>
                                <input type="string" name="cuit_origen" class="form-control">
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Provincia</label>
                                <select 
                                  name="provincia_origen" 
                                  id= "provOrigen"
                                  class="form-control select2"
                                  onchange="getLocalities('origen')"
                                  required
                                 >
                                  <option value selected disabled>Seleccione</option>
                                    @foreach($provinces as $province)
                                      <option value="{{$province->codigo}}"> 
                                      {{$province->descripcion}}
                                      </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Localidad</label>
                                <select 
                                  name="localidad_origen" 
                                  class="form-control select2"
                                  id = 'localidadOrigen'
                                  >
                                  <option value="" disabled>Seleccione</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                              <label>Planta</label>
                              <input type="text" name="planta_origen" class="form-control" required>
                          </div>                           
                </div>
              </div>
            </div>
            <div class="section-body">
                  <div class="card">
                    <div class="card-body">
                      <label for="cabecera">Intervinientes</label>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>CUIT remitente venta comercial</label>
                                    <input type="text" name="cuit_remitente" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>CUIT corredor venta</label>
                                    <input type="text" name="cuit_corredor" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>CUIT representante entregador</label>
                                    <input type="text" name="cuit_representante" class="form-control" required>
                                </div>                                                                                
                    </div>
                  </div>
            </div>
            <div class="section-body">
                  <div class="card">
                    <div class="card-body">
                      <label for="cabecera">Carga</label>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Grano</label>
                                    <select name="grain_id" class="form-control select2">
                                      @foreach($grains as $key => $grain)
                                      <option value="{{$key}}"> {{$grain}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Peso bruto</label>
                                    <input type="text" name="peso_bruto" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Peso tara</label>
                                    <input type="text" name="peso_tara" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>Cosecha</label>
                                    <input type="text" name="cosecha" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>Peso bruto descarga</label>
                                  <input type="text" name="peso_bruto_descarga"  class="form-control" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>Peso tara descarga</label>
                                    <input type="text" name="peso_tara_descarga"  class="form-control" required>
                                </div>    
                    </div>
                  </div>
            </div>
            <div class="section-body">
                  <div class="card">
                        <div class="card-body">
                          <label for="cabecera">Destino</label>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>CUIT</label>
                                        <input type="text" name="cuit_destino" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                      <label>Provincia</label>
                                      <select 
                                        name="provincia_destino" 
                                        id = "provDestino"
                                        class="form-control select2"
                                        onchange="getLocalities('destino')"
                                        required
                                       >
                                        <option value selected disabled>Seleccione</option>
                                          @foreach($provinces as $province)
                                            <option value="{{$province->codigo}}"> 
                                            {{$province->descripcion}}
                                            </option>
                                          @endforeach
                                      </select>
                                  </div>
                                    <div class="form-group col-sm-6">
                                        <label>Localidad</label>
                                        <select name="localidad_destino"  id = 'localidadDestino' class="form-control select2"></select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Planta</label>
                                        <input type="text" name="planta_destino"  class="form-control" required>
                                    </div>                        
                        </div>
                    </div>
                    </div>
                  </div>
            </div>
            <div class="section-body">
                  <div class="card">
                    <div class="card-body">
                      <label for="cabecera">Destinatario</label>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>CUIT</label>
                                    <input type="string" name="destinatario" class="form-control">
                                </div>                  
                    </div>
                  </div>
            </div>
            <div class="section-body">
                  <div class="card">
                    <div class="card-body">
                      <label for="cabecera">Transporte</label>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>CUIT transportista</label>
                                    <input type="string" name="cuit_transportista" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Dominio</label>
                                    <input type="string" name="dominio" class="form-control">
                                    <small id="emailHelp" class="form-text text-muted">
                                      Separados por comas (",")
                                    </small>
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>Fecha de partida</label>
                                  <input type="datetime-local" step="1" name="fecha_partida" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>Km a recorrer</label>
                                  <input type="number" name="km_recorrer" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>Código turno</label>
                                  <input type="text" name="codigo_turno" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>CUIT chofer</label>
                                  <input type="text" name="cuit_chofer" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>Tarifa referencia</label>
                                  <input type="number" name="tarifa_referencia" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>Tarifa</label>
                                  <input type="number" name="tarifa" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>CUIT pagador flete</label>
                                  <input type="text" name="cuit_pagador_flete" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>Mercaderia fumigada</label>
                                  <input type="checkbox" name="mercaderia_fumigada" class="form-control">
                                </div>
                    </div>
                  </div>
            </div>
            <div class="col-12 align-items-center pt-2">
              <button class="btn btn-primary btn-icon" id="save-btn">Guardar</button>
            </div>
        </form>          
      </div>
    </section>

    <script>
    //search localities by province id
    const getLocalities = (inputPrefix) => {
      let localidad = null;
      let codProv   = null;
      if (inputPrefix == 'origen') {
        $("#localidadOrigen").empty();
        localidad = document.getElementById('localidadOrigen');
        codProv   = document.getElementById('provOrigen').value;
      }else{
        $("#localidadDestino").empty();
        localidad = document.getElementById('localidadDestino');
        codProv   = document.getElementById('provDestino').value;
      }
      console.log(codProv);
      $.ajax({
        url: window.location.origin+ '/admin/carta-porte/localities/'+ codProv,
        cache: true,
        type: 'GET',
        dataType: 'json',
        success: function (exists) {
          console.log(localidad, exists);
          if (exists.length) {
            exists.forEach(locality => {
              let opt       = document.createElement('option');
              opt.value     = locality?.codigo;
              opt.innerHTML = locality?.descripcion;
              localidad.appendChild(opt);
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          //
        }
      });
    }
    </script>
    
@endsection



