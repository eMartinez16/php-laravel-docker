<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CameraReport extends Model
{
    use HasFactory, SoftDeletes;

    // basándonos en el archivo que nos pasó el cliente, puede prestarse a cambios
    protected $fillable = [
        'download_date',
        'carta_porte_id',
        'result_number',
        'essay_code',
        'essay_result',
        'kg',
        'grain_id',
        'wagon_number',
        'fee_amount',
        'bonus_or_discount',
        'total_bonus_or_discount',
        'out_of_standard',
        'certificate_number',
        'ctg_number'
    ];

    public function grain()
    {
        return $this->belongsTo(Grain::class, 'grain_id');
    }

    public function cartaPorte()
    {
        return $this->belongsTo(CartaPorte::class, 'carta_porte_id', 'id');
    }

    public function essay()
    {
        return $this->belongsTo(GrainParams::class, 'essay_code', 'code');
    }

    public function getEssayName()
    {
        if (!is_null($this->essay)) {
            return $this->essay->description;
        }
    }
}
