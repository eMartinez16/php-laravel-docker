@extends('layouts.app')

@section('title', 'Facturación')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Facturación</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">       
              <form method="GET" action="{{ route('admin.invoices.index') }}">
                <div class="row align-items-center">
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Cliente:</span>
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
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Puerto:</span>
                    <select class="form-control form-control-sm select2" name="port">
                      <option value="">-- Seleccionar un Puerto</option>
                      @foreach ($ports as $key => $name)
                        <option 
                          value="{{ $key }}" 
                          @if (request('port') == $key) selected @endif
                        >
                          {{ $name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-2 col-sm-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Desde: </span>
                    <input type="date" class="form-control" name="fecha_inicio" value="{{ Request::get('fecha_inicio') }}">
                  </div>
                  <div class="col-lg-2 col-sm-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Hasta: </span>
                    <input type="date" class="form-control" name="fecha_fin" value="{{ Request::get('fecha_fin') }}">
                  </div>
                  <div class="col-lg-2 col-12 d-flex align-items-center justify-content-end">
                    <div>
                      <a 
                        class="btn btn-outline-lighty float-right"
                        href="{{ route('admin.invoices.index') }}" 
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
                </div>
                <table class="table table-sm table-striped">
                  <th>
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="selectAll" > 
                      <label class="custom-control-label" for='selectAll'/>
                    </div>
                  </th>
                  <th class="text-center">N°CP</th>
                  <th class="text-center">N°Ticket descarga</th>
                  <th class="text-center">CUIT</th>
                  <th class="text-center">Cliente</th>
                  <th class="text-center">Puerto</th>
                  <th class="text-center">Grano</th>
                  <th class="text-center">Kilos</th>
                  @foreach ($cps as $cp)
                    <tr>
                      <td>
                        <div class="custom-control custom-checkbox">
                            <input 
                              type="checkbox" 
                              class="select-item custom-control-input" 
                              id="check-{{$cp->numero}}"
                              value = '{{$cp->numero}}'
                              name="select-item" 
                              onchange="appendCp({{$cp->numero}})"
                            >
                            <label class="custom-control-label" for="check-{{$cp->numero}}"></label>
                        </div>
                      </td>
                      <td class="text-center">{{ $cp->numero }}</td>
                      <td class="text-center">{{ $cp->downloadTicket->nro_ticket }}</td>
                      <td class="text-center">{{ $cp->getJson()->origen->cuit }}</td>
                      <td class="text-center">{{ $cp->client }}</td>
                      <td class="text-center">{{ $cp->downloadTicket->port  }}</td>
                      <td class="text-center">{{ $cp->grain}}</td>
                      <td class="text-center">{{ $cp->downloadTicket->commercial_net}}</td>
                    </tr>
                  @endforeach
                </table>
                <form action="{{route("admin.invoices.create")}}" method="post" id="form">
                  @csrf
                  <input type="hidden" name="cps" id="cps">
                  <button class='btn btn-primary btn-lg'> 
                    Facturar
                  </button> 
                </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script>
      let cps = [];
      
      const appendCp = (number) => 
      {
        var all = $("#selectAll")[0];
        all.checked = false
        const isChecked = document.getElementById('check-'+number).checked;
        if(!isChecked){
          cps = cps.filter(c => c != number);
        }

        if(isChecked){ 
          cps.push(number);          
        }
        document.getElementById('cps').value = cps;
      }

      $(function(){
        //button select all or cancel
        $("#selectAll").click(function () {
            var all = $("#selectAll")[0];
            var checked = all.checked;
            $("input.select-item").each(function (index,item) {
                if(!checked){
                  item.checked = checked;
                  cps = cps.filter(c => c != item.value);
                }else{
                  item.checked = checked;
                  cps.push(item.value);
                }
            });
        });      
      });
      form.addEventListener('submit', e => {
        if (cps.length) document.getElementById('cps').value = cps.join(","); 
        else e.preventDefault(); 
    })
    </script>
  @endif
@endsection
