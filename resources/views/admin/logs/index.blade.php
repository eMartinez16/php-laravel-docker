@extends('layouts.app')

@section('title', 'Logs')
@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Logs</h3>
        </div>
        
        <div class="section-body">   
          <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="row align-items-center">
                                <div class="col-lg-3 col-12 pr-0">
                                    <span class="col-sm2 col-form-label m-1"> Desde: </span>
                                    <input type="date" name="from" class="form-control" value="{{$params['from'] ?? ''}}">
                                </div>
                                <div class="col-lg-3 col-12 pr-0">
                                    <span class="col-sm2 col-form-label m-1"> Hasta: </span>
                                    <input type="date" name="to" class="form-control mr-4" value="{{$params['to'] ?? ''}}">
                                </div>
                                <div class="col-lg-4 col-12 pr-0">                                    
                                    <input type="search" name="search" class="form-control mt-4" placeholder="Buscar" value="{{$params['search'] ?? ''}}">
                                </div>
                                <div class="col-lg-1 col-12 pr-0">
                                    <button class="btn btn-primary btn-icon mt-4"><i class="fas fa-search"></i></button>
                                </div>
                                <div class="col-lg-1 col-12 pr-0 text-right">
                                    <a href="{{ route('admin.logs.index') }}" class="btn btn-lighty btn-icon mt-4"><i class="fas fa-redo"></i></a>
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
                                <th class="text-left">Usuario</th>
                                <th class="text-left">Email</th>
                                <th class="text-left">Rol</th>
                                <th class="text-left">Evento</th>
                                <th class="text-left">Entidad</th> 
                                <th class="text-center">ID entidad</th>                           
                                <th class="text-center">Fecha</th>                               
                                @foreach ($logs as $log)
                                <tr>
                                    <td class="text-left">{{$log->user}}</td>
                                    <td class="text-left">{{$log->email}}</td>
                                    <td class="text-left">{{$log->rol}}</td>
                                    <td class="text-left">{{$log->event}}</td>
                                    <td class="text-left">{{$log->entity}}</td>
                                    <td class="text-center">{{$log->entity_id}}</td>                                    
                                    <td class="text-center">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$log->date)->format('d/m/Y')}}</td>                          
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-footer">
          <div class="float-right">
            {{$logs->appends($_GET)->links('pagination::bootstrap-4')}}
          </div>
        </div>           
    </section>
@endsection
