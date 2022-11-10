
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="author" content="Syloper">
  <meta name="application-name" content="VyV-gestión">
  <meta name="description" content="VyV - Resúmen cuenta corriente">
  <title>Cuenta corriente de {{$accounts['accounts'][0]->client->business_name}}</title>
</head>
<style>
  .table thead {
      color: #000000;
      border: 1px solid #000000;
      font-size: 15px;
      line-height: 18px;
  }

  .table th {
      color: #000000;
      border-right: 1px solid #000000;
      font-size: 15px;
      line-height: 18px;
  }

  .table tbody {
      color: #000000;
      border: 1px solid #000000;
      font-size: 15px;
      line-height: 18px;
  }

  .table td {
      color: #000000;
      border-right: 1px solid #000000;
      font-size: 15px;
      line-height: 18px;
  }

  span {
    font-style: italic;
    font-weight: normal;
  }


</style>

<body>  
  <main>
    <section class="section">
      <div class="client-section">
        <h3>Datos del cliente:</h3>
          <h4>Razón social:  <span>{{$accounts['accounts'][0]->client->business_name}}  </span></h4> 
          <h4>CUIT:  <span>{{$accounts['accounts'][0]->client->CUIT}}</span> </h4>
          <h4>Condición fiscal: <span>{{$accounts['accounts'][0]->client->fiscal_condition->nombre}}</span> </h4>
          <h4>Condición de pago: <span>{{$accounts['accounts'][0]->client->payment_condition['value']}}</span> </h4>
      </div>
    </section> 
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead>
              <th >Debe</th>
              <th >Haber</th>
              <th >N°Factura</th>
              <th >N°Comprobante</th>
              <th >Fecha</th>
              <th >Dias de demora</th>
            </thead>
            <tbody>
              @foreach($accounts['accounts'] as $ca)
                <tr>
                  <td>
                    @if($ca->debe)
                    ${{ $ca->debe }}
                    @endif
                  </td>
                  <td>
                    @if($ca->haber)
                    ${{ $ca->haber }}
                    @endif
                  </td>
                  <td>{{ $ca->invoice_id }}</td>
                  <td>{{ $ca->nro_voucher}}</td>                    
                  <td>{{ date('d-m-Y', strtotime($ca->created_at))}}</td>
                  <td>{{$ca->delayDays}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="section-header">
          <h3 class="page__heading">Total ${{$total}}</h3>
      </div>
    </section> 
  </body>
</html> 