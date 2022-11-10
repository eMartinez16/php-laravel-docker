@extends('layouts.app')

@section('title', 'Agregar Usuario')
@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Agregar Usuario</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action=" {{ route('admin.usuario.store') }} ">
                                <div class="row">
                                    @csrf
                                    <div class="form-group col-sm-6">
                                        <label>Nombre</label><span class="required">*</span>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Apellido</label><span class="required">*</span>
                                        <input type="text" name="apellido" id="apellido" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Email</label><span class="required">*</span>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Contraseña</label><span class="required">*</span>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>DNI</label><span class="required">*</span>
                                        <input type="text" name="dni" id="dni" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Teléfono</label><span class="required"></span>
                                        <input type="text" name="telefono" id="telefoßno" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Rol</label><span class="required">*</span>
                                        <select class="form-control select2" name="role" id="role" empty="Selecciona un Rol" required>
                                            @foreach($roles as $k => $role)
                                            <option value="{{$k}}">{{$role}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" action="{{ route('admin.usuario.store') }}" class="btn btn-primary" id="btnPrEditSave" tabindex="5">Guardar</button>
                                    <a href="{{ route('admin.usuario.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </section>
    
@endsection

