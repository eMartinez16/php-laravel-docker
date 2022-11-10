@extends('layouts.app')

@section('title', 'Reportes de Carta de Porte')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Reportes</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">       
              <form method="GET" action="{{ route('admin.carta_porte.reports') }}">
                <div class="row align-items-center">
                  <div class="col-lg-4 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Cliente: </span>
                    <select class="form-control form-control-sm select2" name="client_id">
                      <option value="">-- Seleccionar un Cliente</option>
                      @foreach ($clients as $key => $name)
                        <option 
                          value="{{ $key }}" 
                          @if (request('client_id') == $key) selected @endif
                        >
                          {{ $name }}
                        </option>
                      @endforeach
                    </select>
                  </div>        
                  <div class="col-lg-6 col-12"></div>
                  <div class="col-lg-2 col-12 d-flex align-items-center justify-content-end">
                    <div>
                      <a 
                        class="btn btn-outline-lighty float-right"
                        href="{{ route('admin.carta_porte.reports') }}" 
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
  @if (count($cps) && request('client_id'))
    <section class="section">
      <div class="section-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row col-12 px-0 pb-3 m-0 d-flex align-items-center">
                  <div class="col-sm-10 pl-0">
                    <p class="font-weight-bold mb-0">CANTIDAD DE CARTA DE PORTE: {{ count($cps) }}</p>
                  </div>
                  <div class="col-sm-2 d-flex align-items-center justify-content-end pr-0">
                    <div class="">
                      <a
                        class="btn btn-info" 
                        href="{{ route('admin.carta_porte.reports', 'client_id='.request('client_id').'&download=1')  }}" 
                        title="Descargar reporte"
                      >
                        <i class="fas fa-file-download"></i>
                      </a>
                    </div>
                  </div>
                </div>
                <table class="table table-sm table-striped">
                  <th class="text-center">Nro</th>
                  <th class="text-center">CTG</th>
                  <th class="text-center">Solicitante</th>
                  <th class="text-center">Emisión</th>
                  <th class="text-center">Vto</th>
                  <th class="text-center">Estado</th>
                  <th class="text-center">Transportista</th>
                  {{-- <th class="text-center">Acciones</th> --}}
                  @foreach ($cps as $value)
                    <tr>
                      <td class="text-center">{{ $value->numero }}</td>
                      <td class="text-center">{{ $value->ctg }}</td>
                      <td class="text-center">{{ $value->getJson()->destinatario->cuit }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $value->emision)->format('d/m/Y') }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $value->getJson()->cabecera->fechaVencimiento)->format('d/m/Y') }}</td>
                      <td class="text-center" title='{{$value->getLabelForStatus()}}'>{{ $value->estado }}</td>
                      <td class="text-center">{{ $value->getJson()->transporte->cuitTransportista }}</td>
                      {{-- <td class="text-center"> 
                        <a href="{{ route('admin.carta_porte.show', ['id' => $value->id]) }}" target="_blank" style="text-decoration: none">
                          <i class="fas fa-eye"
                            @if ($value->ticket) style='color: blue' title='Tiene ticket de descarga'
                            @else style='color: gray' title='Sin ticket de descarga' 
                            @endif
                          >
                          </i>
                        </a> 
                        <a href="{{ $value->pdf }}" target="_blank" title="Descargar">
                          <i class="fas fa-cloud-download-alt"></i> 
                        </a>
                      </td> --}}
                    </tr>
                  @endforeach
                </table>
                <div class="float-right mt-3">
                  {{ $cps->appends($_GET)->links('pagination::bootstrap-4') }}
                </div>
                <div class="float-right mt-3">
                  {{ $cps->appends($_GET)->links('pagination::bootstrap-4') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
@endsection
