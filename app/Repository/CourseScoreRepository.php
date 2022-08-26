<?php

namespace App\Repository;

use App\Models\CourseScore;

class CourseScoreRepository extends CourseScore
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
