<?php

namespace App\Repository;

use App\Models\ExamQuestion;

class ExamQuestionRepository extends ExamQuestion
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
