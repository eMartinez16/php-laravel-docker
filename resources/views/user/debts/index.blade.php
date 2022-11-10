@extends('layouts.app')

@section('title', 'Reporte de deudas por cliente')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Reporte de deudas por cliente</h3>
    </div>        
    <div class="section-body">   
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form action="" method="GET">
                <div class="row align-items-center">
                  <div class="col-lg-4 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Cliente: </span>
                    <select class="form-control select2" name="client" id="client">
                      <option value="todos">Todos</option>
                      @foreach($clients as $k => $client)
                        <option value="{{$client->id}}" @if( request()->query('client')==$client->id) selected @endif>{{$client->CUIT}} - {{$client->business_name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Desde:</span>
                    <input type="date" name="date_start" id="date_start" class="form-control" value="{{$params['date_start'] ?? $start}}">
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Hasta:</span>
                    <input type="date" name="date_end" id="date_end" class="form-control mr-4" value="{{$params['date_end'] ?? $end}}">
                  </div>
                  <div class="col-lg-2 col-12 d-flex align-items-center justify-content-end">
                    <div>
                      <a
                        class="btn btn-outline-lighty float-right"
                        href="{{ route('admin.clients.debts.index') }}"
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
  </section>
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <th width="25%">Cliente</th>
                  <th width="25%">Deuda</th>
                  <th width="30%">Fecha ultimo comprobante</th>
                  <th width="20%">Acciones</th>
                </thead>
                <tbody>
                  @foreach($inbox as $ca)
                    <tr>
                      <td>{{ $ca['client']->business_name }}</td>
                      <td>{{ $ca['saldo'] }}</td>
                      <td>{{ $ca['fecha_ult'] }}</td>
                      <td>
                        <a href="{{ route('admin.clients.current-account.index', ['client'=>$ca['client']->id]) }}">
                          <i class="fas fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="row align-items-center">
                <div class="col-lg-12 pr-0">
                  <span class="m-3">Total deuda: </span><span class="btn btn-dark">{{$total_deuda}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
