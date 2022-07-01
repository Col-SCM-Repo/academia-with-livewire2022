<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class StoreEnrollmentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "student_document_number" => "string | required | min:8 ",
            "student_id" => "string | required| min:1 ",
            "student_father_lastname" => "string | required | min:2 ",
            "student_mother_lastname" => "string | required | min:2",
            "student_name" => "string | min:2 | required",
            "student_birth_date" => "string | min:10 | required",
            "student_telephone" => "string ",
            "student_address" => "string ",
            "student_district" => "string | required | min:1",
            "student_district_id" => "string | required | min:1 ",
            "student_ie" => "string | required | min:1",
            "student_ie_id" => "string | required | min:1",
            "student_graduation_year" => "string | required | min:4",
            "relative_document_number" => "string | required | min:4 ",
            "relative_id" => "string ",
            "relative_father_lastname" => "string | required | min:2 ",
            "relative_mother_lastname" => "string | required | min:2",
            "relative_name" => "string | required | min:2",
            "relative_birth_date" => "string | required | min:10",
            "relative_telephone" => "string ",
            "relative_occupation" => "string | required | min:2 ",
            "relative_address" => "string",
            "relative_relationship" => "string | required | min:2 ",
            "type" => "string | required | min:2",
            "classroom" => "string | required | min:1",
            "classroom_id" => "string | required | min:1",
            "career" => "string | required | min:2",
            "career_id" => "string | required | min:1",
            "payment_type" => "string | required | min:2",
            "payment_type_value" => "string ",
            "enrollment_cost" => "string",
            "period_cost" => "string ",
            "fees_quantity" => "string ",
            "observations" => "string ",
            "student_photo_file" => "",
        ];
    }
}
