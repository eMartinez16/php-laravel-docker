<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = [
        'client_id',
        'comprobante_id',
        'importe',
        'fecha_pago',
        'medio_pago_id',
        'nro_comprobante',
        'observaciones',
        'adjunto',
    ];
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');        
    }

    public function medio_pago()
    {
      return $this->belongsTo('App\Models\MediosPagos', 'medio_pago_id');        
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'comprobante_id');
    }

    public function getAdjunto() 
    {      
      return asset('storage/payment/'.$this->id.'/'.$this->adjunto);
    }
}
