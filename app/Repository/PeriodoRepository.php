<?php

namespace App\Repository;

use App\Models\Classroom;
use App\Models\Period;

class PeriodoRepository extends Period
{

    public function builderModelRepository()
    {
        $modelRepository = [
            'id' => 0,
            'name' => '0',
            'year' => 2022,
            'active' => 0,
        ];
        return $modelRepository;
    }


    public function getPeriodoEnrollment($id)
    {
        return Period::with(['levels.enrollments', 'levels.classrooms.enrollments', 'levels.level_type'])->whereId($id)->firstOrFail();
    }

    /* Devuelve informacion de todos los periodos activos  */
    public function getPeriodosAndLevels()
    {
        return Period::with('classrooms.level.level_type')->where('active', 1)->get();
    }

    /* Recibe el ID una clase y muestra informacion de los alumnos y sus apoderados */
    public function getStudentsAndParents($id)
    {
        return Classroom::with('level.level_type', 'enrollments.student.entity', 'enrollments.relative.entity')->whereId($id)->firstOrFail();
    }
}
