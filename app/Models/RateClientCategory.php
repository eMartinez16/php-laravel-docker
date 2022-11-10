<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateClientCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_category_id',
        'rate_id',
        'percentage',
        'total'
    ];

    public function categories(){
        return $this->belongsTo(ClientCategory::class, 'client_category_id');
    }

    public function rates(){
        return $this->belongsTo(Rate::class, 'rate_id');
    }    
}
