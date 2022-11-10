<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="author" content="Syloper">
  <meta name="application-name" content="VyV-gestión">
  <meta name="description" content="VyV - Factura">
  <title>Factura N° {{$invoice->voucher_number}}</title>

  <style>
    @page {
        margin: 25px;
    }

    .body {
        font-size: 11px;
        line-height: 20px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #212121;
    }

    footer {
        position: fixed; 
        bottom: 150px; 
        left: 0px; 
        right: 0px;
    }

    .title {
        font-size: 15px;
        line-height: 20px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #212121;
    }
    .title-tipo-comp{
        font-size: 25px;
        line-height: 20px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #212121;
    }
    
    table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        font-size: 11px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #212121;
    }
    .font-min {
    font-size: 9px;
    }
    .detalle-prod{
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    table td {
        padding: 5px;
        vertical-align: middle;
    }
    
    table tr.heading td {
        background: #ccc;
        font-weight: bold;
    }
    
    table tr.item td{
        border-bottom: 1px solid #eee;
    }
    .font-subtotales{
      font-size: 13px;
    }
    table tr.item.last td {
        border-bottom: none;
    }
    
    table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }        

    .borde{
        border: 1px solid #000;
    }
    .borde-letra{
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }
    .borde-letra-content{
        border-bottom: 1px solid #000;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
    }
    .borde-right{
                    border-right: 1px solid #000;
    }
    .separador{
        height: 5px;
    }
    
    .center{
        text-align: center;
    }

    .right{
        text-align: right;
    }

    .left{
        text-align: left;
    }

    .text-upper{
        text-transform: uppercase;
    }
    
    .espacio-derecho{
        margin-right: 30px
    }

    .padd-0{
        padding: 0 !important;
    }

    .padd-right-10{
        padding-right: 5px !important;
    }

    .mr-50 {
        margin-right: 50px;
    }

    .p{
        margin-bottom: 5px;
        line-height: 7px;
    }

    .sin-borde{
        border-bottom: 0 !important;
    }

    .borde-left{
        border-left: 1px solid #000 !important;
    }

    .borde-top{
        border-top: 1px solid #000 !important;
    }
    .ml-cae{
    margin-left:83px;
    }
    .page_break {
        page-break-before: always;
    }
    .show-whit-space{
      white-space: pre-line;
    }            
  </style>
</head>
<body>  
  {{-- <?php dd($client->payment_condition);?> --}}
  <main>
      <table cellpadding="0" cellspacing="0">
          <tr>
              <td colspan="3" class="padd-0">
                  <table cellpadding="0" cellspacing="0">
                      <tr>
                          <td class="borde center">
                              <b class="text-upper center"> ORIGINAL </b>
                          </td>
                      </tr>
                      <tr>
                        <td class="borde padd-0">
      <table cellpadding="0" cellspacing="0" width="100%" class="padd-0">
                            <tr>
                          <td width="44%">
                              <div class="center">
                                {{-- <img {{business.image|image}} style="width: 200px;"><br><br> --}}
                              </div>
                              <b>Razón Social:</b> <span class="text-upper"> {{$invoice->razon_social}} </span><br>
                              <b>Domicilio Comercial:</b> {{$invoice->domicilio_comercial}}  <br>
                              <b>Condición frente al IVA: {{$invoice->condicion_fiscal}} </b><br><br>
                              {{-- <b>Email:</b> {{$invoice.email}} <br>                                                                         
                              <b>Teléfono:</b> {{$invoice.phone}}  --}}
                          </td>

                          <td width="12%" class="padd-0">
                            <table  cellpadding="0" cellspacing="0" class="padd-0">
                              <tr class="padd-0">
                                <td colspan="2" class="center borde-letra-content padd-0">
                                  <b class="title center" style="font-size: 40px !important;line-height: 40px !important;"> A </b><br>
                                  <b class="title center" style="font-size: 11px !important;"> COD 000000 </b> <br>                                    
                                </td>
                              </tr>
                              <tr>
                                <td class="padd-0"><div class="center" style="border-right:1px solid #000; height:140px"></div></td>
                                <td class="padd-0"></td>
                              </tr>
                            </table>
                          </td>      
                                                  
                          <td width="44%">
                              <b class="title-tipo-comp"> FACTURA </b><br><br><br> <br><br><br>
                              <b>Punto de venta: {{$invoice->punto_venta}} <span class="espacio-derecho"></span> Comp. Nro: {{$invoice->voucher_number}} </b>  <br>                                                                       
                              <b>Fecha de Emisión: {{$invoice->date}}  </b><br><br>      <br>                                                            
                              <b> CUIT :</b> {{$invoice->cuit}} <br>
                              <b>Ingresos Brutos:</b> {{$invoice->cuit}} <br>
                              <b>Fecha de Inicio de Actividades:</b> {{$invoice->fecha_inicio_act}}                                    
                          </td>
                          </tr>
                          </table>
                          </td>
                      </tr>
                  </table>


                  <div class="separador"></div>
                  
                  <table class="borde">
                      <tr>
                          <td width="30%">
                              <b>Periodo Facturado Desde:</b> {{$invoice->voucher_from}} 
                          </td>
                          <td width="40%">
                              <b>Hasta:</b> {{$invoice->voucher_to}} 
                          </td>
                          <td>
                              <b>Fecha de Vto para el pago:</b> {{$invoice->voucher_expiration}} 
                          </td>
                      </tr>
                  </table>
                  
                  <div class="separador"></div>                        

                  <table class="borde">
                      <tr>                                
                          <td>
                            <b>CUIT:</b> {{$client->cuit}} <br>
                            <b>Condición frente al IVA:</b> {{$client->fiscal_condition->nombre}} <br>
                            <b>Condición de venta:</b> {{$client->payment_condition['value']}} </span>

                          </td>
                          
                          <td>
                              <b>Apellido y Nombre / Razón Social: </b><span class="text-upper"> {{$client->business_name}} </span><br>
                              <b>Domicilio: </b> {{$client->residence}} - {{$client->location}} 
                          </td>
                      </tr>
                  </table>
          
                  <div class="separador"></div>
              </td>
          </tr>
      </table>

      <table cellpadding="0" cellspacing="0">
          <tr class="heading">
              <td class="left borde" width="45%">Producto / Servicio</td>
              <td class="center borde" width="9%">Cant.</td>
              <td class="center borde" width="9%">Precio U.</td>
              <td class="center borde" width="9%">% Bonif.</td>
              <td class="center borde" width="9%">Subtotal</td>
              <td class="center borde" width="9%">IVA</td>
              <td class="center borde" width="12%">Subtotal C/IVA</td>
          </tr>
          @foreach($invoice->getFormattedItems() as $item)
            <tr class="item">
                <td class="left detalle-prod">
                <span class="font-min">Cod. {{$item['id']}} </span><br>
                {{$item['grain'] }} * {{$item['port']}} </td>
                <td class="right"> {{round($item['quantity'],2)}} </td>
                <td class="right"> {{round($item['unit_price'],6)}}kg </td>
                <td class="center"> 0,00 </td>
                <td class="right"> {{$item['net']}} </td>
                <td class="right"> {{round($item['iva'],2)}} </td>
                <td class="right"> {{round($item['total'],2)}} </td>
            </tr>
          @endforeach
      </table>

      <footer>
          <table cellpadding="0" cellspacing="0" class="borde">
              <tr class="borde">
                  <td width="50%">
                      {{-- {{observations}}  --}}
                  </td>
                  <td>
                    <table>
                      <tr>
                        <td class="right">
                          <b class="font-subtotales">Importe Neto Gravado: </b><br>
                          <b class="font-subtotales">Importe IVA: </b><br>
                          <b class="font-subtotales">Importe Total:  </b>
                        </td>
                        
                        <td class="right">
                          <span class="font-subtotales"> ${{$invoice->total_net}} </span><br>
                          <span class="font-subtotales"> $ {{$invoice->total_iva}} </span><br>
                          <span class="font-subtotales"><b> ${{$invoice->total_net}} </b></span>
                        </td>
                      </tr>
                    </table>                        
                  </td>
              </tr>
          </table>
          
          <table cellpadding="0" cellspacing="0">
              <tr>
                  <td width="8%">
                    <img src="data:image/png;base64, {{asset('storage/images/QR_AFIP.png')}} " width="70" height="70">
                  </td>
                  <td width="55%">
                    <img {{asset('storage/images/AFIP_LOGO.png')}} width="70">
                    <br>
                    <b style="font-size:12px;">Comprobante Autorizado</b>
                  </td>
                  <td>
                    <span style="font-size:12px;">
                      <b class="ml-cae">CAE Nº:</b> {{$invoice->cae}} <br>
                      <b>Fecha de Vto. de CAE:</b> {{$invoice->cae_expiration}} <br>
                    </span>
                  </td>
              </tr>
          </table>
      </footer>
      
      {{-- <div class="page_break"></div>
                      <table cellpadding="0" cellspacing="0">
          <tr>
              <td colspan="3" class="padd-0">
                  <table cellpadding="0" cellspacing="0">
                      <tr>
                          <td class="borde center">
                              <b class="text-upper center"> DUPLICADO </b>
                          </td>
                      </tr>
                      <tr>
                        <td class="borde padd-0">
      <table cellpadding="0" cellspacing="0" width="100%" class="padd-0">
                            <tr>
                          <td width="44%">
                              <div class="center">
                                <img {{business.image|image}} style="width: 200px;"><br><br>
                              </div>
                              <b>Razón Social:</b> <span class="text-upper"> {{business.name}} </span><br>
                              <b>Domicilio Comercial:</b> {{business.address}} - {{business.city}} ( {{business.postal_code}} ) - {{business.state}} - {{business.country}} <br>
                              <b>Condición frente al IVA: {{business.fiscal_condition.name}} </b><br><br>
                              <b>Email:</b> {{business.email}} <br>                                                                         
                              <b>Teléfono:</b> {{business.phone}} 
                          </td>

                          <td width="12%" class="padd-0">
                            <table  cellpadding="0" cellspacing="0" class="padd-0">
                              <tr class="padd-0">
                                <td colspan="2" class="center borde-letra-content padd-0">
                                  <b class="title center" style="font-size: 40px !important;line-height: 40px !important;"> {{letter}} </b><br>
                                  <b class="title center" style="font-size: 11px !important;"> COD {{type_voucher.code_afip}} </b> <br>                                    
                                </td>
                              </tr>
                              <tr>
                                <td class="padd-0"><div class="center" style="border-right:1px solid #000; height:140px"></div></td>
                                <td class="padd-0"></td>
                              </tr>
                            </table>
                          </td>      
                                                  
                          <td width="44%">
                              <b class="title-tipo-comp"> {{type_voucher.name}} </b><br><br><br> <br><br><br>
                              <b>Punto de venta: {{point_sale.point_sale|pointSales}} <span class="espacio-derecho"></span> Comp. Nro: {{number|numberInvoice}} </b>  <br>                                                                       
                              <b>Fecha de Emisión: {{date|date}}  </b><br><br>      <br>                                                            
                              <b> {{business.type_document.name}} :</b> {{business.documents_number}} <br>
                              <b>Ingresos Brutos:</b> {{business.gross_receipts}} <br>
                              <b>Fecha de Inicio de Actividades:</b> {{business.date_start_activities|date}}                                    
                          </td>
                          </tr>
                          </table>
                          </td>
                      </tr>
                  </table>


                  <div class="separador"></div>
                  
                  <table class="borde">
                      <tr>
                          <td width="30%">
                              <b>Periodo Facturado Desde:</b> {{date|date}} 
                          </td>
                          <td width="40%">
                              <b>Hasta:</b> {{date_expiration|date}} 
                          </td>
                          <td>
                              <b>Fecha de Vto para el pago:</b> {{date_expiration|date}} 
                          </td>
                      </tr>
                  </table>
                  
                  <div class="separador"></div>                        

                  <table class="borde">
                      <tr>                                
                          <td>
                            <b>CUIT:</b> {{customer.data_fiscal.documents_number}} <br>
                            <b>Condición frente al IVA:</b> {{fiscal_condition_customer.name}} <br>
                            <b>Condición de venta:</b> {{method_payment.name}} </span>

                          </td>
                          
                          <td>
                              <b>Apellido y Nombre / Razón Social: </b><span class="text-upper"> {{customer.data_fiscal.business_name}} </span><br>
                              <b>Domicilio: </b> {{customer.data_fiscal.address}} - {{customer.data_fiscal.postal_code}} - {{customer.data_fiscal.city}} - {{customer.data_fiscal.state}} 
                          </td>
                      </tr>
                  </table>
          
                  <div class="separador"></div>
              </td>
          </tr>
      </table>

      <table cellpadding="0" cellspacing="0">
          <tr class="heading">
              <td class="left borde" width="45%">Producto / Servicio</td>
              <td class="center borde" width="9%">Cant.</td>
              <td class="center borde" width="9%">Precio U.</td>
              <td class="center borde" width="9%">% Bonif.</td>
              <td class="center borde" width="9%">Subtotal</td>
              <td class="center borde" width="9%">IVA</td>
              <td class="center borde" width="12%">Subtotal C/IVA</td>
          </tr>
          {{details|arrayStart}} 
          <tr class="item">
              <td class="left detalle-prod">
              <span class="font-min">Cod. {{selectItem.code}} </span><br>
              {{selectItem.name}} </td>
              <td class="right"> {{quantity}} {{selectItem.unit_measurement_purchase_default.reference}} </td>
              <td class="right">  {{price_unit}} </td>
              <td class="center"> {{percentage_discount}} </td>
              <td class="right"> {{amount_net}} </td>
              <td class="right"> {{amount_iva}} </td>
              <td class="right"> {{amount_total}} </td>
          </tr>
          {{details|arrayEnd}} 
      </table>

      <footer>
          <table cellpadding="0" cellspacing="0" class="borde">
              <tr class="borde">
                  <td width="50%">
                    <p class="show-whit-space"> {{observations}} </p>
                  </td>
                  <td>
                    <table>
                      <tr>
                        <td class="right">
                          <b class="font-subtotales">Importe Neto Gravado: {{currency.symbol}} </b><br>
                          <b class="font-subtotales">Importe IVA: {{currency.symbol}} </b><br>
                          <b class="font-subtotales">Importe Total: {{currency.symbol}} </b>
                        </td>
                        
                        <td class="right">
                          <span class="font-subtotales"> {{amount_net}} </span><br>
                          <span class="font-subtotales"> {{amount_iva}} </span><br>
                          <span class="font-subtotales"><b> {{amount_total}} </b></span>
                        </td>
                      </tr>
                    </table>                        
                  </td>
              </tr>
          </table>
          
          <table cellpadding="0" cellspacing="0">
              <tr>
                  <td width="8%">
                    <img src="data:image/png;base64, {{afip_qr}} " width="70" height="70">
                  </td>
                  <td width="55%">
                    <img {{business.image_afip|image}} width="70">
                    <br>
                    <b style="font-size:12px;">Comprobante Autorizado</b>
                  </td>
                  <td>
                    <span style="font-size:12px;">
                      <b class="ml-cae">CAE Nº:</b> {{afip_cae}} <br>
                      <b>Fecha de Vto. de CAE:</b> {{afip_vto_cae}} <br>
                    </span>
                  </td>
              </tr>
          </table>
      </footer> --}}
    </main>  
  </body>
</html> 