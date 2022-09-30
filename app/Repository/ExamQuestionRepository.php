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

    public function actualizarPregunta( int $pregunta_id, string $respuesta ){
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
}
