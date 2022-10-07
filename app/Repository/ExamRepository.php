<?php

namespace App\Repository;

use App\Enums\TipoEvaluacionEnum;
use App\Models\Exam;
use Exception;
use Hamcrest\Type\IsString;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ExamRepository extends Exam
{
    public function __construct()
    {
    }

    public function builderModelRepository()
    {
        return (object) [
            /* 'id' => null, */             // id
            'periodo_id' => null,           // period_id
            'nivel_id' => null,             // level_id
            'grupo_id' => null,             // group_id
            'codigo_grupo' => null,         // group_code
            'nombre' => null,               // name
            'numero_preguntas' => null,     // number_questions
            'puntaje_incorrectas' => null,  // score_wrong
            'tipo_evaluacion' => null,      // evaluation_type
            'fecha_examen' => null,         // exam_date
            //'usuario_id' => null,           // user_id
            'ruta_archivo' => null,         // path
        ];
    }

    public function registrar ( object $modelExam ) {
        // Validaciones
        /* if( ! in_array($modelExam->tipo_evaluacion, [TipoEvaluacionEnum::getArrayTypesEvaluation()]) )
            throw new Exception('No se encontró el tipo de examen'); */

        $examen = new Exam();
        $examen->period_id = $modelExam->periodo_id;
        $examen->level_id = $modelExam->nivel_id;
        $examen->group_id = $modelExam->grupo_id;
        $examen->group_code = $modelExam->codigo_grupo;
        $examen->name = $modelExam->nombre;
        $examen->number_questions = $modelExam->numero_preguntas;
        $examen->score_wrong = $modelExam->puntaje_incorrectas;
        $examen->evaluation_type = $modelExam->tipo_evaluacion;
        $examen->exam_date = $modelExam->fecha_examen;
        $examen->user_id = Auth::user()->id;
        $examen->save();
        return $examen;
    }

    public function actualizar ( int $examen_id, object $modelExam ) {
        $examen =  Exam::find($examen_id);

        if(!$examen) throw new NotFoundResourceException('No se encontro el examen a actualizas');

        $examen->period_id = $modelExam->periodo_id? $modelExam->periodo_id :$examen->period_id;
        $examen->level_id = $modelExam->nivel_id? $modelExam->nivel_id :$examen->level_id;
        $examen->group_id = $modelExam->grupo_id? $modelExam->grupo_id :$examen->group_id;
        $examen->group_code = $modelExam->codigo_grupo? $modelExam->codigo_grupo :$examen->group_code;
        $examen->name = $modelExam->nombre? $modelExam->nombre :$examen->name;
        $examen->number_questions = $modelExam->numero_preguntas? $modelExam->numero_preguntas :$examen->number_questions;
        $examen->score_wrong = $modelExam->puntaje_incorrectas? $modelExam->puntaje_incorrectas :$examen->score_wrong;
        $examen->evaluation_type = $modelExam->tipo_evaluacion? $modelExam->tipo_evaluacion :$examen->evaluation_type;
        $examen->exam_date = $modelExam->fecha_examen? $modelExam->fecha_examen :$examen->exam_date;
        //$examen->user_id = $modelExam->usuario_id? $modelExam->usuario_id :$examen->user_id;
        $examen->path = $modelExam->ruta_archivo? $modelExam->ruta_archivo :$examen->path;
        $examen->save();
        return $examen;
    }

    public function agregarRutaExamen ( int $examen_id, string $ruta_examen ) {
        $examen =  Exam::find($examen_id);

        if(!$examen) throw new NotFoundResourceException('No se encontro el examen a actualizas');
        $examen->path = $ruta_examen;
        return $examen->save();
    }

    public function eliminar ( int $examen_id ) {
        $examen =  Exam::find($examen_id);

        if(!$examen) throw new NotFoundResourceException('No se encontro el examen a actualizas');
        $examen->delete();
        return true;
    }


    // Por evaluar
    public function eliminarPreguntasCursosExamen ( int $examen_id ) {
        $examen = Exam::find($examen_id);
        if(!$examen) throw new NotFoundResourceException('Error, no se encontró el examen ');

        $cursosEliminados = 0;
        $preguntasEliminadas = 0;

        $examen_cursos = $examen->course_scores;
        if(count($examen_cursos)>0)
            foreach($examen_cursos as $curso) {
                $curso->delete();
                $cursosEliminados++;
            }

        $preguntas = $examen->questions;
            if(count($preguntas)>0)
                foreach ($preguntas as $pregunta){
                    $pregunta->delete();
                    $preguntasEliminadas++;
                }
        return "Se elimino $cursosEliminados cursos y $preguntasEliminadas preguntas.";
    }

    public function listaExamenesPorRango( $fecha_inicio, $fecha_fin ){
        $listaExamenes = array();
        foreach (self::whereBetween('exam_date', [$fecha_inicio, $fecha_fin])->get() as $index => $examen) {
            $examen->number_questions = count($examen->questions) ;
            $examen->number_exam_summaries = count($examen->exam_summaries) ;

            $estado = null;
            if( $examen->number_questions > 0 ){
                if( $examen->number_exam_summaries>0 )  $estado = 'REVISADO';
                else {
                    if( $examen->path && is_string($examen->path) && strlen($examen->path)>0  ) $estado = 'PENDIENTE DE REVISAR';
                    else $estado = 'PENDIENTE DE CARGAR';
                }
            }
            else $estado = 'PREGUNTAS NO REGISTRADAS';

            $examen->status = $estado;

            $examen->disabled_cartilla = $estado != 'PREGUNTAS NO REGISTRADAS' ;
            $examen->disabled_corregir = $estado == 'PENDIENTE DE REVISAR' || $estado=='REVISADO' ;
            $examen->disabled_resultados = $estado == 'REVISADO';

            $examen->evaluation_type = TipoEvaluacionEnum::getName($examen->evaluation_type);

            $examen->archivo = null;

            $listaExamenes [$index] = $examen->toArray();
        }
        return $listaExamenes;
    }

}
