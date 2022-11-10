<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'liable',
        'business_name',
        'email',
        'CUIT',
        'phone',
        'location',
        'residence',
        'fiscal_condition_id',
        'payment_condition',
        'category_id',
        'disabled',
    ];

    public function category(){
      return $this->belongsTo('App\Models\ClientCategory', 'category_id');
    }

    public function fiscal_condition() {
      return $this->belongsTo('App\Models\CondicionFiscal', 'fiscal_condition_id');
    }

    public function ca(){
      return $this->hasMany('App\Models\CurrentAccount', 'client_id');        
    }

    public function payment(){
      return $this->hasMany('App\Models\Payment', 'client_id');        
    }

    public function getPaymentConditionAttribute($value) {
      switch ($value) {
        case 'contado':
          return array(
            'reference' => 'contado',
            'value' => 'Contado',
          );
          break;
        case '15_dia':
          return array(
            'reference' => '15_dia',
            'value' => 'CtaCte 15 días',
          );
          break;
        case '30_dia':
          return array(
            'reference' => '30_dia',
            'value' => 'CtaCte 30 días',
          );
          break;
      }        
    }

    public function scopeFindByCUIT($query, $cuit){
        return $query->where('cuit', $cuit);
    }
}
