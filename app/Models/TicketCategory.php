<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'grain_category_id',
        'discount',
        'discount_kg',
        'value'
    ];

    public function categories(){
        return $this->hasOne(GrainCategory::class, 'id','grain_category_id');
    }

    public function getCategoryName(){
        return (!$this->categories) ? null : $this->categories->name;        
    }
}
