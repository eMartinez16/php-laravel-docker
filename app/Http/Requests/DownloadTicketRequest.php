<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nro_carta_porte'       => 'numeric|required',
            'nro_ticket'            => 'numeric|required',
            'nro_contract'          => 'numeric|required',
            'grain_category_id'     => 'numeric|required',
            'discount_kg'           => 'numeric|required',
            'gross_weight'          => 'numeric|required',
            'date_gross_weight'     => 'date|required',
            'tare_weight'           => 'date|required',
            'date_tare_weight'      => 'date|required',
            'net_weight'            => 'numeric|required',
            'total_discount'        => 'numeric|required',
            'comercial_net'         => 'numeric|required',
            'condition'             => 'string|required',
        ];
    }

    public function messages()
    {
        return [
            'nro_carta_porte.numeric'    => 'Ingrese un número de carta de porte válido.',
            'nro_ticket.numeric'         => 'Ingrese un número de ticket válido.',
            'nro_contract.numeric'       => 'Ingrese un número de contrato válido.',
            'grain_category_id.numeric'  => 'Ingrese un número de categoría válido.',
            'discount_kg.numeric'          => 'Ingrese un descuento válido.',
            'gross_weight.numeric'         => 'Ingrese un número de ',
            'date_gross_weight.date'     => 'Ingrese una fecha ',
            'date_tare_weight.date'      => 'Ingrese una fecha',
            'required'                   => 'El :attribute es requerido',
        ];
    }
}
