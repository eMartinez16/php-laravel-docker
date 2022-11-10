@extends('layouts.app')

@section('title', 'Ver reporte de cámara')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Reporte de cámara - Carta de Porte #{{ $cartaPorte->ctg }}</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="row pt-4">
                <div class="col-lg-4 col-12 text-center pb-4">
                  <h5 class="pb-3 d-flex flex-column">
                    <span>CERTIFICADO DE ANÁLISIS</span>
                    <span>N° <span class="text-primary">{{ $cameraReport['certificate_number'] }}</span></span>
                  </h5>
                  <div>
                    <label class="font-weight-bold">DESCARGA:</label>
                    <span>{{ date_format(date_create($cameraReport['download_date']), 'd/m/y') }}</span>
                  </div>
                  <div> 
                    <label class="font-weight-bold">KILOGRAMOS:</label> 
                    <span>{{ number_format($cameraReport['kg'], 0, ',', '.') }}</span>
                  </div>
                  <div>
                    <label class="font-weight-bold">C.P.:</label>
                    <span>{{ $cartaPorte->id }}</span>
                  </div>
                  <div> 
                    <label class="font-weight-bold">VAGON:</label>
                    <span>{{ $cameraReport['wagon_number'] || '' }}</span>
                  </div>
                  <div> 
                    <label class="font-weight-bold">C.T.G:</label>
                    <span>{{ number_format($cartaPorte->ctg, 0, ',', '.') }}</span>
                  </div>
                </div>
                <div class="col-lg-8 col-12">
                  <h5 class="text-center pb-3">ANÁLISIS DE <span class="text-muted">{{ $grain->name }}</span></h5>
                  <div >
                    <ul class="list-group">
                      @foreach ($cameraReport['essays'] as $essayName => $essayResult)
                        @if ($essayName !== 'DURO' && $essayName !== 'GRADO')
                          <li class="list-group-item d-flex justify-content-between">
                            <p class="mb-0">{{ $essayName }}</p>
                            <p class="mb-0">{{ $essayResult }}@if ($essayName === 'PESO HECTOLITRICO') kg @else % @endif</p>
                          </li>
                        @endif
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              @if ($cameraReport['fee_amount'])
                <div class="col-12 text-right py-2">
                  <label class="font-weight-bold mb-0">HONORARIOS:</label> 
                  ${{ $cameraReport['fee_amount'] }}
                </div>
              @endif
              <div class="col-12 pl-0 pb-0">
                @if($cartaPorteType == 'Automotor')
                  <a href="{{ route('admin.carta_porte.indexAuto') }}" class="btn btn-light m-1">Regresar</a>
                @else
                  <a href="{{ route('admin.carta_porte.indexFerro') }}" class="btn btn-light m-1">Regresar</a>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection