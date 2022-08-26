<?php

namespace App\Repository;

use App\Models\Course;

class CourseRepository extends Course
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

    public function registrar ( object $modelCurso ) {

    }

    public function actualizar ( int $curso_id, object $modelCurso ) {

    }

    public function eliminar ( int $curso_id ) {

    }


}
