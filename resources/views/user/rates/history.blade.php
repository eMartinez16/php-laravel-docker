@extends('layouts.app')

@section('title', 'Historial')
@section('content')
  <section class="section">
    <div class="section-header">
        <h3 class="page__heading">Historial</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <th class="text-left">N° Tarifa</th>
                            <th class="text-left">Categoria</th>
                            <th class="text-left">% a pagar</th>
                            <th class="text-left">Total tarifa</th>
                            <th class="text-left">Total a pagar</th>
                            <th class="text-left">Fecha de creación</th>
                            @foreach ($ratesCategory as $rate)
                              <tr>  
                                  <td class="text-left">{{$rate->rates->id}}</td>
                                  <td class="text-left">{{$rate->categories->name}}</td>
                                  <td class="text-left">{{$rate->percentage.'%'}}</td>
                                  <td class="text-left">{{$rate->rates->value}}</td>
                                  <td class="text-left">{{$rate->total}}</td>
                                  <td class="text-left">{{date('d-m-Y',strtotime($rate->created_at))}}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{route('admin.rates.index')}}" class='btn btn-primary btn-icon'>Volver</a>
    </div>
  </section>
@endsection