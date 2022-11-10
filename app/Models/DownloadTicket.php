<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nro_carta_porte',
        'nro_ticket',
        'nro_contract',
        'discount_kg',
        'gross_weight',
        'date_gross_weight',
        'tare_weight',
        'date_tare_weight',
        'net_weight',
        'total_discount',
        'commercial_net',
        'condition',
        'observation',
        'port'
    ];

    public function categories()
    {
        return $this->hasManyThrough(GrainCategory::class, TicketCategory::class, 'ticket_id', 'id', 'id', 'grain_category_id');
    }
    
    public function ticketCategory()
    {
        return $this->hasMany(TicketCategory::class, 'ticket_id');
    }
}
