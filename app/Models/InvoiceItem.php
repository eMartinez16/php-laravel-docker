<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'carta_porte_id',
        'invoice_id',
        'total',
        'iva',
        'neto'
    ];

    public function invoice(){
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function scopeFindByInvoiceId($query, $invoice_id)
    {
        return $query->where('invoice_id', $invoice_id);
    }
}
