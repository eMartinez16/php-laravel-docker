@extends('layouts.app')

@section('title', 'Ver Usuario')
@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Ver Usuario</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <ul>
                                    <li><b>Nombre: </b>{{$user->name}}</li>
                                    <li><b>Apellido: </b>{{$user->apellido}}</li>
                                    <li><b>Email: </b>{{$user->email}}</li>
                                    <li><b>DNI: </b>{{$user->dni}}</li>
                                    <li><b>Teléfono: </b>{{$user->telefono}}</li>
                                    <li><b>Rol: </b>{{$roles[$user->role]}}</li>
                                    <li><b>Estado: </b>{{$estados[$user->estado]}}</li>
                                </ul>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.usuario.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Regresar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </section>
    
@endsection