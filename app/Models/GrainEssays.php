<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrainEssays extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['grain_id', 'grain_param_id', 'result'];
}
