@extends('layouts.app')

@section('title', 'Agregar Cliente')
@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Agregar Cliente</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action=" {{ route('admin.clients.store') }} ">
                                <div class="row">
                                    @csrf
                                    <div class="form-group col-sm-6">
                                        <label>Responsable</label>
                                        <input type="text" name="liable" id="liable" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Razón social</label>
                                        <input type="te xt" name="business_name" id="business_name" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>CUIT</label>
                                        <input type="text" name="CUIT" id="CUIT" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Teléfono</label>
                                        <input type="text" name="phone" id="phone" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Domicilio</label>
                                        <input type="text" name="residence" id="residence" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Localidad</label>
                                        <input type="text" name="location" id="location" class="form-control" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Categoria</label>
                                        <select class="form-control select2" name="category_id" id="category_id" empty="Selecciona una Categoria" required>
                                            @foreach($categories as $k => $category)
                                            <option value="{{$k}}">{{$category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Condición de pago</label>
                                        <select class="form-control select2" name="payment_condition" id="payment_condition" empty="Selecciona una condicion de pago" required>
                                            @foreach($payment_condition as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
                                    <a href="{{ route('admin.clients.index') }}" class="btn btn-light ml-1 edit-cancel-margin margin-left-5">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </section>
    
@endsection



