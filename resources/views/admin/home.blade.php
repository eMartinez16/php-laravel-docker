@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Dashboard</h3>
        </div>
        <div class="section-body">
            <div class="row">
                @if(Auth::user()->role == 'administrador')
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Total Usuarios</h4>
                        </div>
                        <div class="card-body">
                        {{$countUsers}}
                        </div>
                    </div>
                    </div>
                </div>
                @endif
                
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                    <div class="card-icon bg-dark">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Total Clientes</h4>
                        </div>
                        <div class="card-body">
                        {{$countClients}}
                        </div>
                    </div>
                    </div>
                </div>
          
            </div>
        </div>
    </section>
@endsection

