<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'voucher_number',
        'sale_point',
        'voucher_type',
        'voucher_from',
        'voucher_to',
        'voucher_expiration',
        'cae',
        'cae_expiration',
        'concept',
        'total_net',
        'total_exempt',
        'total_iva',
        'total',
        'date'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function getFormattedItems()
    {
        if(!$this->items) return null;

        $formattedItems = [];
        foreach($this->items as $item){
            $cp = CartaPorte::find($item->carta_porte_id);
            $formattedItems[] = [
                'id'       => $item->id,
                'net'      => $item->neto,
                'iva'      => $item->iva,
                'total'    => $item->total,
                'grain'    => $cp->grain,
                'port'     => $cp->downloadTicket->port,
                'quantity' => $cp->downloadTicket->commercial_net,
                'unit_price' => $cp->downloadTicket->commercial_net / $item->total,
            ];
        }

        return $formattedItems;
    }

}
