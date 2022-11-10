<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'value'
    ];
   
    public function categories(){
        return $this->belongsToMany(ClientCategory::class, 'rate_client_categories', 'rate_id', 'client_category_id');
    }    
}
