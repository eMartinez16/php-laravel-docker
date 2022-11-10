@component('mail::message')

    Cliente : {{$cobro->client->business_name}}
    CUIT: {{ $cobro->client->CUIT }}

    Datos del cobro:
    Cobro N°: {{ $cobro->id }}
    Número comprobante : {{$cobro->nro_comprobante}}
    Número factura : {{$cobro->invoice->voucher_number}}
    Fecha de pago : {{$cobro->fecha_pago}} 
    Importe : {{$cobro->importe}} 
    Observaciones : {{$cobro->observaciones}} 


@endcomponent