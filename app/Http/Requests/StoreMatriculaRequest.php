<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatriculaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "type"=> "string | required",
            "classroom_id" => "string | required | min:1",
            "career_id" => "string | required | min:1",
            "payment_type_value"=> "string | required | min:2",
            "enrollment_cost"=> "string | required ",
            "period_cost"=> "string | required",
            "fees_quantity"=> "string | required",
            //"career" => "string | required",
            //"classroom" => "string | required",
            //"observations"=> "string"
        ];
    }
    
    public function messages()
    {
        return [
            'type.required'  => 'El campo :attribute es obligatorio.',
            'classroom_id.required'  => 'El campo :attribute es obligatorio.',
            'career_id.required'  => 'El campo :attribute es obligatorio.',
            'payment_type_value.required'  => 'El campo :attribute es obligatorio.',
            'enrollment_cost.required'  => 'El campo :attribute es obligatorio.',
            'period_cost.required'  => 'El campo :attribute es obligatorio.',
            'fees_quantity.required'  => 'El campo :attribute es obligatorio.',
        ];
    }


}
