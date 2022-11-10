@extends('layouts.app')

@section('title', 'Ver Carta de Porte')
@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Carta de Porte - {{$str_tipo}}</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                              <link rel=stylesheet href=https://cdn.jsdelivr.net/npm/pretty-print-json@1.2/dist/pretty-print-json.css>
                              <script src=https://cdn.jsdelivr.net/npm/pretty-print-json@1.2/dist/pretty-print-json.min.js></script>

                              <pre id=account class=json-container></pre>
                              <script>
                                const data = {!! $data->json !!};
                                data.ticket_descarga = {!! json_encode($data->ticket) !!};       
                                const elem = document.getElementById('account');
                                elem.innerHTML = prettyPrintJson.toHtml(data);
                              </script>
                            </div>
                            <div class="col-12 d-flex flex-row justify-content-between pr-0">
                              @if($str_tipo == 'Automotor')
                                <a href="{{ route('admin.carta_porte.indexAuto') }}" class="btn btn-light m-1">Regresar</a>
                              @else
                                <a href="{{ route('admin.carta_porte.indexFerro') }}" class="btn btn-light m-1">Regresar</a>
                              @endif

                              @if(isset($data->ticket))
                              <div class="d-flex">                                                           
                                <a href="{{ route('admin.ticket.show', $data->ticket['id']) }}" class="btn btn-light m-1 ">Editar ticket</a>

                                <form action="{{ route('admin.ticket.destroy', $data->ticket['id']) }}" method="POST">
                                  @csrf
                                  @method('delete')
                                  <button class="btn btn-danger m-1" onclick="return confirm('Se eliminará el ticket. ¿Continuar?')">Eliminar ticket</button>
                                </form>
                              </div>     

                              @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </section>
    
@endsection