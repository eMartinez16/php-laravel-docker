@extends('layouts.app')

@section('title', 'Listado de Cartas de Porte')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Carta de Porte - {{ $str_tipo }}</h3>

      @if (explode('/', url()->current())[5] == 'auto')
        <div class="dropdown ml-4">
          <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Opciones</button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="javascript:void(0);" onclick="action('form-obtener-auto', 'Sincronizar todas las cartas de portes')">Sincronizar</a>   
            <form 
              action="{{ route('admin.carta_porte.importCTG') }}" 
              class='dropdown-item' 
              enctype="multipart/form-data" 
              id='importCTG' 
              method="post" 
              style="padding: 10px 20px !important; font-size: 13px !important;"
            >
              @csrf
              <label class="mb-0" id='import' style="cursor: pointer;">Importar CTG</label>
              <input 
                accept=".txt"
                hidden 
                id="archivo" 
                name="archivo" 
                onchange="uploadCsv(this)" 
                type="file" 
              />
              <input type="hidden" value='auto' name='tipo'/>
            </form>
            {{-- store       --}}
            <a class="dropdown-item" href="{{route('admin.carta_porte.create', 'auto')}}">Crear CP</a>             
          </div>
        </div>
      @endif

      @if (explode('/', url()->current())[5] == 'ferro')
        <div class="dropdown ml-4">
          <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Opciones</button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="javascript:void(0);" onclick="action('form-obtener-ferro', 'Sincronizar todas las cartas de portes')">Sincronizar</a>       
            <form 
              action="{{ route('admin.carta_porte.importCTG') }}" 
              class='dropdown-item' 
              enctype="multipart/form-data" 
              id='importCTG' 
              method="post" 
              style="padding: 10px 20px !important; font-size: 13px !important;"
            >
              @csrf
              <label class="mb-0" id='import' style="cursor: pointer;">Importar CTG</label>
              <input 
                accept=".txt"
                hidden 
                id="archivo" 
                name="archivo" 
                onchange="uploadCsv(this)" 
                type="file" 
              />
                <input type="hidden" value='ferro' name='tipo'/>
            </form>
            <a class="dropdown-item" href="{{route('admin.carta_porte.create', 'ferro')}}">Crear CP</a> 
          </div>
        </div>
      @endif
    </div>
    

    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form 
                method="GET" 
                @if (explode('/', url()->current())[5] == 'auto') action="{{ route('admin.carta_porte.indexAuto') }}" 
                @else action="{{ route('admin.carta_porte.indexFerro') }}" 
                @endif
              >
                <div class="row align-items-center">
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> CUIT solicitante: </span>
                    <input type="text" class="form-control" name="solicitante" value="{{ Request::get('solicitante') }}">
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> CTG </span>
                    <input type="text" class="form-control" name="ctg" value="{{ Request::get('ctg') }}">
                  </div>
                  <div class="col-lg-2 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Estado: </span>
                    <select class="form-control form-control-sm select2" name="estado">
                      <option value="">-- Seleccionar un Estado</option>
                      @foreach ($estados as $key => $item)
                        @if (Request::get('estado') == $item['estado'])
                          <option value="{{ $item['estado'] }}" selected>{{ $item['estado'] }}</option>
                        @else
                          <option value="{{ $item['estado'] }}">{{ $item['estado'] }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-1 col-sm-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Desde: </span>
                    <input type="date" class="form-control" name="fecha_inicio" value="{{ Request::get('fecha_inicio') }}">
                  </div>
                  <div class="col-lg-1 col-sm-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Hasta: </span>
                    <input type="date" class="form-control" name="fecha_fin" value="{{ Request::get('fecha_fin') }}">
                  </div>
                  <div class="col-lg-2 col-12 d-flex align-items-center justify-content-end">
                    <div>
                      <a
                        class="btn btn-outline-lighty float-right"
                        @if (explode('/', url()->current())[5] == 'auto') href="{{ route('admin.carta_porte.indexAuto') }}"
                        @else href="{{ route('admin.carta_porte.indexFerro') }}"
                        @endif
                      >
                        <i class="fas fa-redo" title="Vaciar filtros"></i>
                      </a>
                    </div>
                    <div class="pl-2">
                      <button type="submit" class="btn btn-primary btn-lg" title="Buscar">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
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
              <table class="table table-sm table-striped">
                <th></th>
                <th class="text-center">Nro</th>
                <th class="text-center">CTG</th>
                <th class="text-center">Solicitante</th>
                <th class="text-center">Emisión</th>
                <th class="text-center">Vto</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Transportista</th>
                <th class="text-center">Acciones</th>
                {{-- <th></th>
                <th></th>
                <th></th> --}}
                @foreach ($data as $value)
                {{-- @php 
                dd($value);
                @endphp --}}
                  <tr>
                    <td>
                      <form method="POST" action="{{ route('admin.carta_porte.actualizar', ['tipo' => $value->tipo, 'sucursal' => $value->sucursal, 'numero' => $value->numero, 'page' => Request::get('page')]) }}">
                        @csrf
                        <button class="btn btn-link" title="Actualizar Datos">
                          <i class="fas fa-sync"></i>
                        </button>
                      </form>
                    </td>
                    <td class="text-center">{{ $value->numero }}</td>
                    <td class="text-center">{{ $value->ctg }}</td>
                    <td class="text-center">{{ $value->getJson()->origen->cuit }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $value->emision)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $value->getJson()->cabecera->fechaVencimiento)->format('d/m/Y') }}</td>
                    <td class="text-center" title='{{$value->getLabelForStatus()}}'>{{ $value->estado }}</td>
                    <td class="text-center">{{ $value->getJson()->transporte->cuitTransportista }}</td>
                    <td class="text-center">

                      {{-- @if (explode('/', url()->current())[5] == 'auto') --}}
                      <a href="{{ route('admin.carta_porte.show', ['id' => $value->id]) }}" target="_blank" style="text-decoration: none">
                        <i class="fas fa-eye"
                          @if ($value->ticket) style='color: blue' title='Tiene ticket de descarga'
                          @else style='color: gray' title='Sin ticket de descarga' 
                          @endif
                        ></i>
                      </a>
                      {{-- @else
                          <a href="{{route('admin.carta_porte.show', ['id'=> $value->id])}}" target="_blank" title="Ver más">
                            <i class="fas fa-eye"></i>
                          </a>                                                
                        @endif --}}

                        {{-- @todo: Agregar condicion de si tiene facturas cambiar color y apuntar a function para ver facturas --}}
                      <a href="{{route('admin.invoices.download', $value->id)}}" target="_blank" title="Descargar factura" style="text-decoration: none">
                        <i class="fas fa-file-invoice-dollar" 
                        @if($value->status == 'facturada') style='color: blue' title='Tiene factura'
                        @else style='color: gray' title='Sin factura'  
                        @endif
                        >
                        </i>
                      </a>

                      {{-- no se muestra el pdf para las cp cargadas manualmente --}}
                      @if($value->estado != 'M')
                        <a href="{{ $value->pdf }}" target="_blank" title="Descargar CP" style="text-decoration: none">
                          <i class="fas fa-cloud-download-alt"></i>
                        </a>
                      @endif

                      <span title="No tiene reporte de cámara">
                        <a
                          href="{{ route('admin.cameraReport.show', ['id' => $value->id, 'type' => json_decode($value->json)->cabecera->tipoCartaPorte]) }}" 
                          style="@if(!$value->cameraReport) pointer-events: none; color: grey; @endif"
                          target="_blank" 
                        >
                          <i
                            class="fas fa-file"
                            style='color: @if ($value->cameraReport) blue @else gray @endif' 
                            title='Ver reporte de cámara'
                          ></i>
                        </a>
                      </span>
                    </td>
                  </tr>
                @endforeach
              </table>
              <div class="float-right mt-3">
                {{ $data->appends($_GET)->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <form id="form-obtener-auto" method="POST" action="{{ route('admin.carta_porte.forceSaveAuto') }}">
    @csrf
    <input name="cuit" type="hidden" value="20252697897">
    <input name="sucursal" type="hidden" value="0">
    <input name="numero" type="hidden" value="154">
    <input name="ctg" type="hidden" value="10106542863">
  </form>
  <form id="form-obtener-ferro" method="POST" action="{{ route('admin.carta_porte.forceSaveFerro') }}">
    @csrf
    <input name="cuit" type="hidden" value="">
    <input name="sucursal" type="hidden" value="">
    <input name="numero" type="hidden" value="">
    <input name="ctg" type="hidden" value="">    
  </form>

  <script>  
    function action(form, action) {
      if (confirm('Continuar con: ' + action) == true) {
        $('#' + form).submit();
      }
    }

    const fileInput = document.getElementById('import').addEventListener('click', () => {
      document.getElementById('archivo').click();
    })

    function uploadCsv(input) {
      document.getElementById('importCTG').submit();
    }
  </script>
@endsection
