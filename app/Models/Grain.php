<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function percentages()
    {
        return $this->hasMany(GrainPercentage::class, 'grain_id', 'id');
    }

    public function categories()
    {
        return $this->hasManyThrough(GrainCategory::class, CategoriesGrains::class, 'grain_id', 'id', 'id', 'grain_category_id');
    }

    public function scopeFindByName($query, $name)
    {
        return $query->where('name', 'LIKE', '%'.$name.'%');
    }
}
