<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrainPercentageRequest extends FormRequest
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
            'grain_id' => 'required|integer',
            'max_value' => 'required|integer',
            'percentage' => 'required|integer'
        ];
    }    

    public function messages()
    {
        return [
            'business_name.required' => 'El campo grano es requerido.',
            'business_name.integer' => 'El campo grano tiene que ser un valor numerico.',
            'liable.required' => 'El campo valor hasta es requerido.',
            'liable.integer' => 'El campo valor hasta tiene que ser un valor numerico.',
            'phone.required' => 'El campo porcentaje de rebaja es requerido.',
            'phone.integer' => 'El campo porcentaje de rebaja tiene que ser un valor numerico.'
        ];
    }
}
