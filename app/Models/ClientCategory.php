<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ClientCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function clients(){
      return $this->hasMany('App\Models\Client', 'fiscal_condition_id');        
    }

    /**
     * get `tarifa`
     *
     * @return void
     */
    public function rate(){
        return $this->hasManyThrough(Rate::class, RateClientCategory::class, 'client_category_id', 'id', 'id', 'rate_id');
    }
}
