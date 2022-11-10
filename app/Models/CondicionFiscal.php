<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionFiscal extends Model
{
    use HasFactory;
    
    protected $table = 'condicion_fiscal';
    protected $fillable = [
        'codigo',
        'nombre',
    ];

    public function clients(){
        return $this->hasMany('App\Models\Client', 'fiscal_condition_id');        
    }
}
