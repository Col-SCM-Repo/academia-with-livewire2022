<?php

namespace App\Repository;

use App\Models\StudentExamCodes;

class StudentExamCodesRepository extends StudentExamCodes
{
    public function __construct()
    {
    }

    public function builderModelRepository()
    {
        return (object) [
            'id' => null,
        ];
    }

}
