<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrainParams extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'description'];

    // public function grain()
    // {
    //     return $this->belongsTo(Grain::class, 'grain_id');
    // }
    // public function getGrainName()
    //     {
    //         return (!is_null($this->grain)) ? $this->grain->name : null;
    //     }
    

    public function scopeFindByCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
