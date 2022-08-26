<?php

namespace App\Repository;

use App\Models\ExamSummary;

class ExamSummaryRepository extends ExamSummary
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
