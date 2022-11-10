<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentAccount extends Model
{
    use HasFactory;

    protected $table = 'current_accounts';
    protected $fillable = [
        'client_id',
        'nro_voucher',
        'invoice_id',
        'debe',
        'haber',
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');        
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }


}
