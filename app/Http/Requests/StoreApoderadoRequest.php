<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApoderadoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            "relative_document_number" => "string | required | min:8 ",
            "relative_id" => "string  ",
            "relative_father_lastname" => "string | required | min:2 ",
            "relative_mother_lastname" => "string | required | min:2",
            "relative_name" => "string | required | min:2",
            "relative_birth_date" => "string | min:10",
            "relative_telephone" => "string ",
            "relative_occupation" => "string ",
            "relative_address" => "string ",
            "relative_relationship" => "string | required | min:2 ",
        ];
    }
    public function messages()
    {
        return [
            'relative_document_number.required'=> 'El campo :attribute es obligatorio  ',
            'relative_id.required'=> 'El campo :attribute es obligatorio.',
            'relative_father_lastname.required'=> 'El campo :attribute es obligatorio.',
            'relative_mother_lastname.required'=> 'El campo :attribute es obligatorio.',
            'relative_name.required'=> 'El campo :attribute es obligatorio.  ',
            'relative_birth_date.required'=> 'El campo :attribute es obligatorio.',
            'relative_telephone.required'=> 'El campo :attribute es obligatorio.',
            'relative_occupation.required'=> 'El campo :attribute es obligatorio.',
            'relative_address.required'=> 'El campo :attribute es obligatorio.',
            'relative_relationship.required'=> 'El campo :attribute es obligatorio.',
        ];
    }
    

}
