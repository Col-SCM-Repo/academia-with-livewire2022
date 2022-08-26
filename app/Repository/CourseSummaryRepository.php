<?php

namespace App\Repository;

use App\Models\CourseSummary;

class CourseSummaryRepository extends CourseSummary
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
