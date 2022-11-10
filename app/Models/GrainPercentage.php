<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrainPercentage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['grain_id', 'max_value', 'percentage', 'grain_category_id'];
    
    public function grain() 
    {
        return $this->belongsTo(Grain::class, 'grain_id');
    }

    public function getGrainName()
    {
        return (!is_null($this->grain)) ? $this->grain->name : null;
    }

    public function grainCategory()
    {
        return $this->belongsTo(GrainCategory::class, 'grain_category_id');
    }

    public function getGrainCategory()
    {
        return (!is_null($this->grainCategory)) ? $this->grainCategory->name : null;
    }
}
