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
            'resumen_examen_id' => null,        // exam_summary_id
            'curso_id' => null,                 // course_id
            'numero_correctas' => null,         // correct_answers
            'numero_incorrectas' => null,       // wrong_answers
            'numero_blanco' => null,            // blank_answers
            'respuestas_estudiante' => null,    // student_responses
            'puntaje_correctos'=> null,         // correct_score
            'puntaje_incorrectos'=> null,       // wrong_score
            'puntaje_por_pregunta' => null ,    // score_question
            /* 'puntaje_total'=> null,             // course_score */
        ];
    }

    public function registrar ( object $modeloCourseSummary ){
        $cursoResumen = new CourseSummary();
        $cursoResumen->exam_summary_id = $modeloCourseSummary->resumen_examen_id;
        $cursoResumen->course_id = $modeloCourseSummary->curso_id;
        $cursoResumen->correct_answers = $modeloCourseSummary->numero_correctas;
        $cursoResumen->wrong_answers = $modeloCourseSummary->numero_incorrectas;
        $cursoResumen->blank_answers = $modeloCourseSummary->numero_blanco;
        $cursoResumen->student_responses = $modeloCourseSummary->respuestas_estudiante;
        $cursoResumen->correct_score = $modeloCourseSummary->puntaje_correctos;
        $cursoResumen->wrong_score = $modeloCourseSummary->puntaje_incorrectos;
        $cursoResumen->score_question = $modeloCourseSummary->puntaje_por_pregunta;
        /* $cursoResumen->course_score = $modeloCourseSummary->puntaje_total; */
        $cursoResumen->save();
        return $cursoResumen;
    }


}
