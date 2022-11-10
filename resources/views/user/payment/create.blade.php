@extends('layouts.app')

@section('title', 'Nuevo Cobro')

@section('content')

  <style>
    .disabled {
      pointer-events: none;
      opacity: 0.6;
    }
  </style>
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Nuevo Cobro</h3>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form method="POST" action=" {{ route('admin.clients.payment.save') }} " enctype="multipart/form-data">
                <div class="row">
                  @csrf
                  <div class="form-group col-sm-6">
                    <label>Cliente</label>
                    <select
                      class="form-control select2"
                      name="client_id"
                      id="cliente_id"
                      required
                      onchange="onSelectClient()"
                    />
                      <option value selected disabled>--Selecciona un Cliente</option>
                      @foreach ($clientes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>Factura / CP</label>
                    <select 
                      class="form-control select2" 
                      name="comprobante_id" 
                      id="comprobante_id" 
                      required
                      onchange="onSelectInvoiceCP()"
                    >
                      <option value selected disabled>--Selecciona una Factura</option>
                    </select>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>Fecha Pago</label>
                    <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" required>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>Importe</label>
                    <input type="number" step="0.01" min="0" name="importe" id="importe" class="form-control" required>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>Medio de Pago</label>
                    <select class="form-control select2" name="medio_pago_id" id="medio_pago_id" required>
                      <option value selected disabled>--Selecciona un Medio de Pago</option>
                      @foreach ($medios_pagos as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-sm-6">
                    <label>N° Comprobante</label>
                    <input type="number" step="0.01" min="0" name="nro_comprobante" id="nro_comprobante" class="form-control" required>
               
                    <label>Adjuntar</label>
                    <input type="file" name="adjunto" id="adjunto" class="form-control"  disabled>
                  </div>
                  <div class="form-group col-sm-12">
                    <label>Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="5" style="height:100%;"></textarea>  
                  </div>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary" tabindex="5">Guardar</button>
                  <a 
                    class="btn btn-light ml-1 edit-cancel-margin margin-left-5"
                    href="{{ route('admin.clients.payment.index') }}" 
                  >
                    Cancelar
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    let clientId         = null;
    let invoiceId     = null;
    let clientSelect     = document.getElementById('cliente_id');
    let cartaPorteSelect = document.getElementById('comprobante_id');
    let adjuntoInput    = document.getElementById('adjunto');
    let importeInput = document.getElementById('importe');
    let comprobanteNum = document.getElementById('nro_comprobante');
    let actualCPs        = [];

    const FACTURADA = 'facturada';

    /**
     * Method triggered when the user selects a client.
     * Get the invoiced and closed cartas porte and sets them in the `carta porte` select
     * @return {void}
     */
    const onSelectClient = () => {
      clientId = clientSelect.value;
    
      $("#cliente_id option").each(function() {
        if ($(this).text() === clientId)
          $(this).prop("selected", "selected");
      });

      const actualURL = `${window.location.origin}/admin/clients/payment/invoiced-closed-cps/${clientId}`;

      $.ajax({
        url: actualURL,
        cache: true,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
          setCPSelect(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(jqXHR, textStatus, errorThrown);
        }
      });
    }

    /**
     * Sets the data to the `carta porte` select
     * @param {[]} data
     * @return {void}
     */
    const setCPSelect = (data = []) => {
      /* Remove all select options at start */
      $(cartaPorteSelect).empty();
      //saco clases y seteo en null cada vez que se cambia el selector del cliente
      $(importeInput).val(null);
      importeInput.classList.remove('disabled');
      $(comprobanteNum).val(null);
      comprobanteNum.classList.remove('disabled');
      adjuntoInput.disabled = true;

      actualCPs = data;

      /* Create by default the first disabled non-selectable option */ 
      let baseOption = document.createElement('option');
      baseOption.selected = baseOption.disabled = true;
      baseOption.innerHTML  = '--Selecciona una Factura'
      cartaPorteSelect.appendChild(baseOption)

      if (data.length) {
        data.forEach((item) => {
          let opt       = document.createElement('option');
          opt.value     = item?.invoice.id;
          opt.innerHTML = 'Comprobante N°'+item?.invoice.voucher_number;
          cartaPorteSelect.appendChild(opt);
        });
      }
    }
    
    /**
     * Method triggered when the user selects a `carta porte`
     * @return {void}
     */
    const onSelectInvoiceCP = () => {    
      invoiceId = cartaPorteSelect.value;

      $("#comprobante_id option").each(function() {
        if ($(this).text() === invoiceId)
          $(this).prop("selected", "selected");
      });

      // si es una carta de porte facturada se carga automáticamente el num comprobante y el importe total
      const actualURL = `${window.location.origin}/admin/clients/payment/invoice/${invoiceId}`;

      $.ajax({
        url: actualURL,
        cache: true,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
          setInvoiceData(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(jqXHR, textStatus, errorThrown);
        }
      });
    }

    //Cargo class y valores para numero de comprobante e importe total de factura
    const setInvoiceData = (data) => {
      if(data.total){
        importeInput.value = data.total
        adjuntoInput.disabled = false;
      }
    }
  </script>
@endsection  