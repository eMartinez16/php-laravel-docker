<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrainCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grain_categories';

    protected $fillable = [
        'grain_id',
        'name'
    ];

    public function grains() 
    {
        return $this->hasManyThrough(Grain::class, CategoriesGrains::class, 'grain_category_id', 'id', 'id', 'grain_id');
    }

    /**
     * @return string[]
     */
    public function getGrainNames() 
    {
        $names = [];
        if (!is_null($this->grains)) {
            $this->grains->map(function($grain) use (&$names) {
                $names[] = $grain->name;
            });
        }
        return $names;
    }
}
