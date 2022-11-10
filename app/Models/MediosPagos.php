<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediosPagos extends Model
{
    use HasFactory;
    
    protected $table = 'medios_pagos';
    protected $fillable = [
        'name',
    ];

    public function payment(){
        return $this->hasMany('App\Models\Payment', 'medio_pago_id');        
    }
}
