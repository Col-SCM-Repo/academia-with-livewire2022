<?php

namespace App\Repository;

use App\Models\ExamSummary;
use Exception;
use Illuminate\Support\Facades\Auth;

class ExamSummaryRepository extends ExamSummary
{
    public function __construct()
    {
    }

    public function builderModelRepository()
    {
        return (object) [
            /* 'id' => null, */
            'examen_id' => null,                // exam_id
            'matricula_id' => null,             // enrollment_id
            'codigo_examen' => null,            // code_exam
            'codigo_estudiante' => null,        // code_enrollment
            'tipo_estudiante' => null,          // student_type
            'numero_correctas' => 0,         // correct_answer
            'numero_incorrectas' => 0,       // wrong_answer
            'numero_blancos' => 0,           // blank_answer
            'puntaje_correcta' => 0,         // score_correct
            'puntaje_incorrecta' => 0,       // score_wrong
            'puntaje_final' => 0,            // final_score
            'apellidos' => null,                // surname
            'nombre' => null,                   // name
            'observacion' => null,              // observation
            'usuario_id' => Auth::user()->id,   // user_id
        ];
    }

    public function registrar( object $modelExamenResumen ){
        $resumenExamnExistente =  self::buscarResumenExamenPorCodigoExamen( (int) $modelExamenResumen->examen_id, (string) $modelExamenResumen->codigo_estudiante);
        if($resumenExamnExistente)
            return self::inicializarEliminarResumenExmn($resumenExamnExistente);

        $examenResumen = new ExamSummary();
        $examenResumen->exam_id = $modelExamenResumen->examen_id;
        $examenResumen->enrollment_id = $modelExamenResumen->matricula_id;
        $examenResumen->code_exam = $modelExamenResumen->codigo_examen;
        $examenResumen->code_enrollment = $modelExamenResumen->codigo_estudiante;
        $examenResumen->student_type = $modelExamenResumen->tipo_estudiante;

        $examenResumen->correct_answer = $modelExamenResumen->numero_correctas;
        $examenResumen->wrong_answer = $modelExamenResumen->numero_incorrectas;
        $examenResumen->blank_answer = $modelExamenResumen->numero_blancos;
        $examenResumen->score_correct = $modelExamenResumen->puntaje_correcta;
        $examenResumen->score_wrong = $modelExamenResumen->puntaje_incorrecta;
        $examenResumen->final_score = $modelExamenResumen->puntaje_final;

        $examenResumen->surname = $modelExamenResumen->apellidos;
        $examenResumen->name = $modelExamenResumen->nombre;
        $examenResumen->observation = $modelExamenResumen->observacion;
        $examenResumen->user_id = $modelExamenResumen->usuario_id;
        $examenResumen->save();
        return $examenResumen;
    }

    public function inicializarEliminarResumenExmn( $examenResumen ){
        if(!$examenResumen) throw new Exception("No se encontro el examen id");

        $examenResumen->correct_answer = 0;
        $examenResumen->wrong_answer = 0;
        $examenResumen->blank_answer = 0;
        $examenResumen->score_correct = 0;
        $examenResumen->score_wrong = 0;
        $examenResumen->final_score = 0;
        $examenResumen->observation = 'RESUMEN ACTUALIZADO';
        $examenResumen->save();

        foreach ($examenResumen->course_summary as $resumenCurso)
            $resumenCurso->delete();

        return $examenResumen;
    }

    public function actualizarPuntajeResumen(   int $resumen_examen_id, int $numero_correctas, int $numero_incorrectas, int $numero_blanco,
                                                $puntaje_correctas, $puntaje_incorrectas  ){
        $examenResumen = ExamSummary::find($resumen_examen_id);
        if(!$examenResumen) throw new Exception("No se encontro el examen con id $resumen_examen_id");

        $examenResumen->correct_answer = $numero_correctas;
        $examenResumen->wrong_answer = $numero_incorrectas;
        $examenResumen->blank_answer = $numero_blanco;
        $examenResumen->score_correct = $puntaje_correctas;
        $examenResumen->score_wrong = $puntaje_incorrectas;
        $examenResumen->final_score = $puntaje_correctas - $puntaje_incorrectas;
        $examenResumen->observation = 'PUNTAJES ACTUALIZADOS';
        $examenResumen->save();

        return $examenResumen;
    }


    public function eliminarExamenResumen(   int $resumen_examen_id  ){
        $examenResumen = ExamSummary::find($resumen_examen_id);
        if(!$examenResumen) throw new Exception("No se encontro el examen con id $resumen_examen_id"); return;

        foreach ($examenResumen->course_summary as $resumenCurso)
            $resumenCurso->delete();

        $examenResumen->delete();
        return true;
    }

    public function buscarResumenExamenPorCodigoExamen( int $examen_id, string $codigo_estudiante ){
        return self::where('exam_id', $examen_id)->where('code_enrollment', $codigo_estudiante)->first();
    }



}
