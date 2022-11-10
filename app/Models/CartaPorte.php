<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartaPorte extends Model
{
    use HasFactory;
    
    protected $table = 'cartas_portes';    
    protected $fillable = [
        'tipo',
        'sucursal',
        'numero',
        'ctg',
        'emision',
        'estado',
        'pdf',
        'json',
    ]; 
    protected $appends = [
        'referencia',
    ];

    //Todos los estados que maneja cada carta de porte
    const STATUS = [
        'AC' => 'Activa',
        'AN' => 'Anulada',
        'CF' => 'Confirmación de arribo',
        'RE' => 'Rechazada',
        'CN' => 'Confirmada',
        'DE' => 'Desactivada',
        'BR' => 'Borrador',
        'M'  => 'Manual'
    ];


    public function getJson() 
    {
      return json_decode($this->json);
    }

    public function getReferenciaAttribute() 
    {
      return 'CARTA DE PORTE ' . str_pad($this->sucursal, 4, "0", STR_PAD_LEFT) . '-' . str_pad($this->numero, 8, "0", STR_PAD_LEFT);
    }

    public function downloadTicket()
    {
      return $this->hasOne(DownloadTicket::class, 'nro_carta_porte', 'numero');
    }

    public function cameraReports()
    {
      return $this->hasMany(CameraReport::class, 'carta_porte_id', 'id');
    }
    
    public function invoice()
    {
        return $this->hasOneThrough(Invoice::class, InvoiceItem::class, 'carta_porte_id', 'id', 'id', 'invoice_id');
    }
    
    public function getGrainAttribute()
    {
        $grain_id = intval($this->getJson()->datosCarga->codGrano);

        if($grain_id){
            $exist = Grain::find($grain_id);

            return ($exist) ? $exist->name : '';
    
        }
    }

    public function getClientAttribute()
    {
        $cuit = intval($this->getJson()->origen->cuit);

        if($cuit){
            $client = Client::findByCUIT($cuit)->first();
            return ($client) ? $client->business_name : '';
        }
    }

    public function getTicketAttribute()
    {      
      if(!$this->downloadTicket) return null;
        
      //todo, crear array no por nombre de categoria sino por id de tabla `ticket_categories`
      foreach ($this->downloadTicket->ticketCategory as $downTicket) {
        $rubros[$downTicket->getCategoryName()] = [
            'descuento'     => $downTicket->discount,
            'valor'         => $downTicket->value,
            'descuento_kg'  => $downTicket->discount_kg,
        ];
      }
      
      return [
        'id'               => $this->downloadTicket->id,
        'nro_ticket'       => $this->downloadTicket->nro_ticket,
        'nro_contrato'     => $this->downloadTicket->nro_contract,
        'peso_bruto'       => $this->downloadTicket->gross_weight,
        'fecha_peso_bruto' => $this->downloadTicket->date_gross_weight,
        'peso_tara'        => $this->downloadTicket->tare_weight,
        'fecha_peso_tara'  => $this->downloadTicket->date_tare_weight,
        'peso_neto'        => $this->downloadTicket->net_weight,
        'total_descuento'  => $this->downloadTicket->total_discount,
        'neto_comercial'   => $this->downloadTicket->commercial_net,
        'condicion'        => $this->downloadTicket->condition,
        'rubros'           => $rubros ?? null
      ];
    }

    public function getLabelForStatus()
    {
        if (array_key_exists($this->estado, self::STATUS)) {
            return self::STATUS[$this->estado];
        }
    }

    public function scopeFindByCTG($query, $ctgNumber)
    {
        return $query->where('ctg', $ctgNumber);
    }

    public function scopeFindByNumber($query, $cpNumber)
    {
        return $query->where('numero', $cpNumber);
    }

    public function scopeFindLastCPNumber($query)
    {
        return $query->orderBy('numero', 'desc');
    }
    
    public function getCameraReportAttribute()
    {
        if (!count($this->cameraReports)) return null;
        $data = [];
        $essays = [];
        foreach ($this->cameraReports as $cameraReport) {
            $essays[$cameraReport->getEssayName()] = $cameraReport->essay_result;
            
            $data[$cameraReport->carta_porte_id] = [
                'download_date'           => $cameraReport->download_date,
                'carta_porte_id'          => $cameraReport->carta_porte_id,
                'kg'                      => $cameraReport->kg,
                'grain_id'                => $cameraReport->grain_id,
                'wagon_number'            => $cameraReport->wagon_number,
                'fee_amount'              => $cameraReport->fee_amount,
                'bonus_or_discount'       => $cameraReport->bonus_or_discount,
                'total_bonus_or_discount' => $cameraReport->total_bonus_or_discount,
                'out_of_standard'         => $cameraReport->out_of_standard,
                'certificate_number'      => $cameraReport->certificate_number,
                'ctg_number'              => $cameraReport->ctg_number
            ];

            $data[$cameraReport->carta_porte_id]['essays'] = $essays;
        }
        
        return $data;
    }
}
