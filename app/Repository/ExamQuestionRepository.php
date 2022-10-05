<?php

namespace App\Repository;

use App\Models\ExamQuestion;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ExamQuestionRepository extends ExamQuestion
{
    private $_examenRepository;

    public function __construct()
    {
        $this->_examenRepository = new ExamRepository();
    }


    public function generarRegistros( int $examen_id ){
        $examen = $this->_examenRepository::find($examen_id);
        if(!$examen) throw new NotFoundResourceException('Error, no se encontró el examen');

        if(count($examen->questions)>0) self::eliminarRegistros($examen_id);

        $cursos = $examen->course_scores;
        if(count($cursos)==0) throw new Exception('No se encontro informacion de los cursos');

        $preguntas = array();
        $numeroPregunta = 0;

        foreach ( $cursos as $curso_scores ) {
            for ($i=0; $i < $curso_scores->number_questions ; $i++) {
                $numeroPregunta++;
                $preguntas[] = [
                    'exam_id' => $examen_id,
                    'course_id' => $curso_scores->course_id,
                    'question_number' => $numeroPregunta,
                    'score' => $curso_scores->score_correct,
                    'correct_answer' => null,
                ];
            }
        }

        return ExamQuestion::insert($preguntas);
    }

    public function actualizarPregunta( int $pregunta_id, $respuesta ){
        $pregunta = self::find($pregunta_id);
        if( ! $pregunta ) throw new NotFoundResourceException('Error, no se encontró a la pregunta');

        $pregunta->correct_answer = $respuesta;
        $pregunta->save();
        return $pregunta;
    }

    public function eliminarRegistros( int $examen_id ){
        $examen = $this->_examenRepository::find($examen_id);
        if(!$examen) throw new NotFoundResourceException('Error, no se encontró el examen');

        $preguntas = $examen->questions;
        if(count($preguntas)>0)
            foreach ($preguntas as $pregunta) $pregunta->delete();

        return count($preguntas).' preguntas eliminadas';
    }

    public function getPreguntasExamen( int $examen_id ){
        $cursos             = array();
        $preguntas          = array();
        $cursoActual        = -999;
        $nombreCurso        = null;
        $nombreCursoCorto   = null;

        $buildExamInfo  = fn(int  $id, int $num, $ptn, $rpta='' ) => [ "id"=>$id, "numero" => $num, "puntaje" => $ptn, "respuesta" => $rpta ];
        $buildCursoInfo = fn($id,$nombre,$shortName,$preguntas) => [ "id" => $id, "nombre" => $nombre, "nombre_corto" => $shortName, "numero" => count($preguntas), "preguntas" => $preguntas];

        $ultimo_curso_id = null ;
        foreach (self::where('exam_id', $examen_id)->orderBy('question_number')->get() as $index => $pregunta){
            $ultimo_curso_id = $pregunta->course_id;
            if($cursoActual != $pregunta->course_id){   // revisar por que esta almacenando el couseid en score
                if( $cursoActual>=0 ) $cursos [$index] = $buildCursoInfo($pregunta->course_id, $nombreCurso, $nombreCursoCorto, $preguntas );
                $preguntas = array();
                $preguntas[] = $buildExamInfo($pregunta->id, $pregunta->question_number, $pregunta->score, $pregunta->correct_answer);
                $cursoActual = $pregunta->course_id;
                $nombreCurso = $pregunta->course->name;
                $nombreCursoCorto = $pregunta->course->shortname;
            }
            else $preguntas[] = $buildExamInfo($pregunta->id, $pregunta->question_number, $pregunta->score, $pregunta->correct_answer);
        }

        if($ultimo_curso_id) $cursos [] = $buildCursoInfo($ultimo_curso_id, $nombreCurso, $nombreCursoCorto, $preguntas);

        return $cursos;
    }


}
