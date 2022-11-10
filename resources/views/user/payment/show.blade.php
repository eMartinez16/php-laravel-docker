@extends('layouts.app')

@section('title', 'Cobro')
@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Cobro</h3>
        </div>
    </section>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <ul>
                                <li><b>Cliente: </b>{{$data->client->liable}}</li>                                
                                <li><b>Número factura: </b>{{$data->comprobante_id}}</li>
                                <li><b>Número comprobante: </b>{{$data->nro_comprobante}}</li>
                                <li><b>Fecha pago: </b>{{\Carbon\Carbon::createFromFormat('Y-m-d',$data->fecha_pago)->format('d/m/Y')}}</li>                                
                                <li><b>Importe: </b>$ {{$data->importe}}</li>
                                <li><b>Medio pago: </b>{{$data->medio_pago->name}}</li>
                                @if($data->adjunto)
                                  <li><b>Adjunto: </b><a href="{{$data->getAdjunto()}}" target="_blank">Ver Mas</a></li>
                                @endif
                                <li><b>Observaciones: </b>{{$data->observaciones}}</li>
                            </ul>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.clients.payment.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Regresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection  