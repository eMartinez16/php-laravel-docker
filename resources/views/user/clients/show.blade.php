@extends('layouts.app')

@section('title', $client->business_name)
@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Ver Cliente</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <ul>
                                    <li><b>Responsable: </b>{{$client->liable}}</li>
                                    <li><b>Razón social: </b>{{$client->business_name}}</li>
                                    <li><b>Email: </b>{{$client->email}}</li>
                                    <li><b>CUIT: </b>{{$client->CUIT}}</li>
                                    @if($client->fiscal_condition)
                                      <li><b>Condicion fiscal: </b>{{$client->fiscal_condition->nombre}}</li>
                                    @else 
                                      <li><b>Condicion fiscal: </b>[NO DISPONIBLE]</li>
                                    @endif
                                    <li><b>Teléfono: </b>{{$client->phone}}</li>
                                    <li><b>Domicilio: </b>{{$client->residence}}</li>
                                    <li><b>Localidad: </b>{{$client->location}}</li>
                                    <li><b>Categoria: </b>{{(!is_null($client->category)) ? $client->category->name : null }}</li>
                                    <li><b>Condición de pago: </b>{{$client->payment_condition['value']}}</li>
                                </ul>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.clients.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Regresar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </section>
    
@endsection