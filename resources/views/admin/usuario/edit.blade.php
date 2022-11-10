@extends('layouts.app')

@section('title', 'Editar usuario')
@section('content')

<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Editar Usuarios</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action=" {{ route('admin.usuario.update', $user->id) }} ">
                            <div class="row">
                                @csrf
                                @method('PUT')
                                <div class="form-group col-sm-6">
                                    <label>Nombre</label><span class="required">*</span>
                                    <input type="text" name="name" id="name" class="form-control" value="{{$user->name}}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Apellido</label><span class="required">*</span>
                                    <input type="text" name="apellido" id="apellido" class="form-control" value="{{$user->apellido}}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Email</label><span class="required">*</span>
                                    <input type="email" name="email" id="email" class="form-control" value="{{$user->email}}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>DNI</label><span class="required">*</span>
                                    <input type="text" name="dni" id="dni" class="form-control" value="{{$user->dni}}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Teléfono</label><span class="required"></span>
                                    <input type="text" name="telefono" id="telefoßno" class="form-control" value="{{$user->telefono ?? ""}}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Rol</label><span class="required">*</span>
                                    <select class="form-control select2 select2-ajax-roles" name="role" id="role" empty="Selecciona un legajo" required>
                                        @foreach($roles as $k => $role)
                                        <option value="{{$k}}" @if($user->role == strtolower($role)) selected @endif>{{$role}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button type="submit" action="{{ route('admin.usuario.update', $user->id) }}" class="btn btn-primary" id="btnPrEditSave" tabindex="5">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col col-lg-12 align-self-end">
                <div class="card">
                    <form method="POST" class="needs-validation" action="{{ route('admin.usuario.changePassword') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}">
                        
                        <div class="card-header">
                            <h4>Cambiar Contraseña</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="password" class="d-block">Contraseña</label>
                                    <input id="password" type="password" minlength="8" class="form-control pwstrength @error('password') is-invalid @enderror" required data-indicator="pwindicator" name="password" autocomplete="new-password">
                                <div id="pwindicator" class="pwindicator">
                                    <div class="bar"></div>
                                    <div class="label"></div>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="password2" class="d-block">Confirmar Contraseña</label>
                                    <input id="password2" type="password" minlength="8" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6 text-left">
                                    <a class="btn btn-secondary" href="{{route('admin.usuario.index')}}">Volver</a>
                                </div>
                                <div class="col-6 text-right">
                                    <button class="btn btn-primary">Cambiar Contraseña</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>

</section>
    
@endsection
