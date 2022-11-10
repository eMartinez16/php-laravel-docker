@extends('layouts.app')

@section('title', 'Cuenta corriente')
@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Cuenta corriente</h3>
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
                      <option value > Seleccione un cliente</option>
                      @foreach($clients as $k => $client)
                        <option value="{{$k}}" @if( request()->query('client')==$k) selected @endif> {{$client}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Desde: </span>
                    <input type="date" 
                      name="from" 
                      class="form-control" 
                      value="                        
                        @if(request()->query('from'))
                          {{request()->query('from')}}
                        @endif                        
                       ">
                  </div>
                  <div class="col-lg-3 col-12 pr-0">
                    <span class="col-sm2 col-form-label m-1"> Hasta: </span>
                    <input type="date" 
                      name="to" 
                      class="form-control mr-4" 
                      value="
                      @if(request()->query('to')) 
                        {{request()->query('to')}}
                       @endif
                       ">
                  </div>
                  <div class="col-lg-2 col-12 d-flex align-items-center justify-content-end">
                    <div>
                      <a
                        class="btn btn-outline-lighty float-right"
                        href="{{ route('admin.clients.current-account.index') }}"
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
  @if (count($currentaccounts['accounts']) && request('client'))
    <section class="section">
      <div class="section-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-lg-6 pr-0">
                    <span class="m-3">
                      @if ($amount <= 0) Cuenta al dia @else Adeudado @endif
                    </span>
                    <span class="btn @if($amount <= 0) btn-light @else btn-dark @endif">
                      @if ($amount <= 0) $ {{  abs($amount) }} @else ${{ $amount }} @endif
                    </span>
                  </div>
                  <div class="col-lg-6 text-right pr-0">
                    <a href = {{route('admin.clients.current-account.downloadPDF', request('client'))}} class="btn btn-success m-3">Descargar</a>
                  </div>
                </div>
                <table class="table table-striped">
                  <thead>
                    <th width="20%">Debe</th>
                    <th width="20%">Haber</th>
                    <th width="20%">N°Factura</th>
                    <th width="20%">N°Comprobante</th>
                    <th width="20%">Fecha</th>
                    <th width="20%">Dias de demora</th>
                  </thead>
                  <tbody>
                    @foreach($currentaccounts['accounts'] as $ca)
                      <tr>
                        <td>
                          @if($ca->debe)
                          ${{ $ca->debe }}

                          @endif
                        </td>
                        <td>@if($ca->haber)
                              ${{ $ca->haber }}
                            @endif
                       </td>
                        <td>{{ $ca->invoice_id }}</td>
                        <td>{{ $ca->nro_voucher}}</td>                    
                        <td>{{ date('d-m-Y', strtotime($ca->created_at))}}</td>
                        <td>{{$ca->delayDays}}</td>
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
    @endif
  @endsection
