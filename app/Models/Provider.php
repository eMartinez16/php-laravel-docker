<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_name',
        'contact',
        'cuit',
        'description',
        'cost_center_id'
    ];

    public function centerCost () : BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    /**
     * return `cost_center` of one provider
     *
     * @return CostCenter|null
     */ 
    public function getCostAttribute()
    {        
        return 
        (!is_null($this->centerCost)) 
        ? $this->centerCost->description
        : null;
    }

}
