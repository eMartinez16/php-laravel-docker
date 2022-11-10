<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'business_name' => 'required',
            'liable' => 'required',
            'email' => 'required',
            'CUIT' => 'required|integer',
            'phone' => 'required',
            'location' => 'required',
            'residence' => 'required',
            'payment_condition'=> 'required',
            'category_id' => 'required'
        ];
    }    

    public function messages()
    {
        return [
            'business_name.required' => 'El campo razón social es requerido.',
            'liable.required' => 'El campo responsable es requerido.',
            'phone.required' => 'El campo teléfono es requerido.',
            'email.required' => 'El campo email es requerido.',       
            'category_id.required' => 'Debe seleccionar una categoria.',
            'CUIT.integer' => 'El CUIT debe ser numérico.',
            'CUIT.required' => 'El campo CUIT es requerido.',            
        ];
    }
}
