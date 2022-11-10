@extends('layouts.app')

@section('title', 'Editar Cobro')
@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Editar Cobro</h3>
        </div>
    </section>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action=" {{ route('admin.clients.payment.save') }} " enctype="multipart/form-data">
                            <div class="row">
                                @csrf
                                <input type="hidden" value="{{$data->id}}" name="id">

                                <div class="form-group col-sm-6">
                                    <label>Cliente</label>
                                    <select class="form-control select2" name="cliente_id" id="cliente_id" empty="--Selecciona un Cliente" required>
                                        @foreach($clientes as $key => $value)
                                          @if($key==$data->id)
                                            <option value="{{$key}}" selected>{{$value}}</option>
                                          @else 
                                            <option value="{{$key}}"  >{{$value}}</option>
                                          @endif
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-6">
                                    <label>Factura / CP</label>
                                    <select class="form-control select2" name="comprobante_id" id="comprobante_id" empty="--Selecciona una Factura" disabled>
                                      <option value="{{$data->voucher_number}}" selected>Comprobante N°{{$data->nro_comprobante}}</option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-6">
                                    <label>Fecha Pago</label>
                                    <input type="date" name="fecha_pago" value="{{$data->fecha_pago}}" id="fecha_pago" class="form-control" required>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Importe</label>
                                    <input type="number"  value="{{$data->importe}}" step="0.01" min="0" name="importe" id="importe" class="form-control" required>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Medio de Pago</label>
                                    <select class="form-control select2" name="medio_pago_id" id="medio_pago_id" empty="--Selecciona un Medio de Pago" required>
                                        @foreach($medios_pagos as $key => $value)
                                          @if($key==$data->id)
                                            <option value="{{$key}}" selected>{{$value}}</option>
                                          @else
                                            <option value="{{$key}}">{{$value}}</option>
                                          @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-6">                                    
                                    <label>Adjuntar</label> 
                                    @if($data->adjunto)
                                      <a href="{{$data->getAdjunto()}}" target="_blank">(Ver adjunto cargado)</a>
                                    @endif
                                    <input type="file" name="adjunto" id="adjunto" class="form-control" require>
                                </div>  
                                
                                <div class="form-group col-sm-12">
                                  <label>Observaciones</label>
                                  <textarea class="form-control summernote-simple" id="summernote-simple" name="observaciones" rows="5" style="height:100%;">
                                    {{$data->observaciones}}
                                  </textarea>  
                                  <script>
                                    $(document).ready(function() {
                                      $('#summernote').summernote();
                                    });
                                  </script>                                    
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
                                <a href="{{ route('admin.clients.payment.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection  