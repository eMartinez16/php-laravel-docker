@extends('layouts.app')

@section('title', 'Cobros')
@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Cobros</h3>
    </div>        
    <div class="section-body">   
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form action="" method="GET">
                <div class="row align-items-center">
                  <div class="col-lg-2 col-12 pr-0 d-flex flex-wrap justify-content-between">
                    <a class="btn btn-primary btn-lg" href="{{ route('admin.clients.payment.create') }}" title="Agregar">Nuevo Cobro</a>
                  </div>
                  <div class="col-lg-2 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Cliente:</span>
                    <select class="form-control select2" name="client" id="client">
                      <option value="">Seleccione un cliente</option>
                      @foreach($clients as $k => $client)
                        <option value="{{$client->id}}" @if( request()->query('client')==$client->id) selected @endif>{{$client->CUIT}} - {{$client->business_name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-2 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Desde:</span>
                    <input type="date" name="from" class="form-control" value="{{$params['from'] ?? ''}}">
                  </div>
                  <div class="col-lg-2 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Hasta:</span>
                    <input type="date" name="to" class="form-control mr-4" value="{{$params['to'] ?? ''}}">
                  </div>
                  <div class="col-lg-2 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1">Estado:</span>
                    <select class="form-control select2" name="status" id="status">
                      <option value="" @if( request()->query('status')=='') selected @endif>Todo</option>
                      <option value="true" @if( request()->query('status')=='true') selected @endif>Con Comprobante</option>
                      <option value="false" @if( request()->query('status')=='false') selected @endif>Sin Comprobante</option>
                    </select>
                  </div>
                  <div class="col-lg-2 col-12 d-flex align-items-center justify-content-end">
                    <div>
                      <a
                        class="btn btn-outline-lighty float-right"
                        href="{{ route('admin.clients.payment.index') }}"
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
                  <th>Cliente</th>
                  <th>N° Comprobante</th>
                  <th>Valor</th>
                  <th>Fecha Pago</th>
                  <th>Recibo</th>
                  <th>Acciones</th>
                </thead>
                <tbody>
                  @foreach($data as $value)                                    
                  <tr>
                    <td>{{$value->client->liable}}</td>
                    <td>{{$value->nro_comprobante}}</td>
                    <td>{{$value->importe}}</td>
                    <td>{{\Carbon\Carbon::createFromFormat('Y-m-d',$value->fecha_pago)->format('d/m/Y')}}</td>
                    <td>
                      @if($value->adjunto)
                        <a href="{{$value->getAdjunto()}}" target="_blank" class="btn btn-link btn-sm">Ver Más</a>
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('admin.clients.payment.show', $value->id) }}" class="btn btn-link" title="Ver">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('admin.clients.payment.edit', $value->id) }}" class="btn btn-link" title="Editar">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
