<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entity',
        'entity_id',
        'event',
        'message',
        'user_id',
        'user',
        'email',
        'rol',
        'date',
    ];
}
