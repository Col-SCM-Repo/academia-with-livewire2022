<?php

namespace App\Repository;

use App\Models\Exam;

class ExamRepository extends Exam
{
    public function __construct()
    {
    }

    public function builderModelRepository()
    {
        return (object) [
            'id' => null,                   // id
            'periodo_id' => null,           // period_id
            'nivel_id' => null,             // level_id
            'grupo_id' => null,             // group_id
            'codigo_grupo' => null,         // group_code
            'nombre' => null,               // name
            'numero_preguntas' => null,     // number_questions
            'tipo_evaluacion' => null,      // evaluation_type
            'fecha_examen' => null,         // exam_date
            'usuario_id' => null,           // user_id
        ];
    }

    public function registrar ( object $modelExam ) {

    }

    public function actualizar ( int $examen_id, object $modelExam ) {

    }

    public function eliminar ( int $examen_id ) {

    }


}
