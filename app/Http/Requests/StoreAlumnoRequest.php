<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlumnoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {   
        return [
            "student_document_number"=> "string | required | min:8",
            "student_id"=> "string",
            "student_father_lastname"=> "string | required | min:2",
            "student_mother_lastname"=> "string | required | min:2",
            "student_name"=> "string | required | min:2",
            "student_birth_date"=> "string | min:10",
            "student_address"=> "string",
            "student_district_id"=> "string | required | min:1",
            "student_ie_id"=> "string | required | min:1",
            "student_graduation_year"=> "string | required | min:4",
            //"student_district"=> "string | required | min:2",
            //"student_ie"=> "string | required | min:1",
            //"student_telephone"=> "string",
            //"student_photo_file"=> "string | required | min:4",
            ];
    }
    
    public function messages()
    {
        return [
            'student_document_number.required'=> 'El campo documento de identidad es obligatorio  ',
            'student_id.required'=> 'El campo :attribute es obligatorio.',
            'student_father_lastname.required'=> 'El campo :attribute es obligatorio.',
            'student_mother_lastname.required'=> 'El campo :attribute es obligatorio.',
            'student_name.required'=> 'El campo :attribute es obligatorio.  ',
            'student_birth_date.required'=> 'El campo :attribute es obligatorio.',
            'student_address.required'=> 'El campo :attribute es obligatorio.',
            'student_district_id.required'=> 'El campo :attribute es obligatorio.',
            'student_ie_id.required'=> 'El campo :attribute es obligatorio.',
            'student_graduation_year.required'=> 'El campo :attribute es obligatorio.',
        ];
    }
}
