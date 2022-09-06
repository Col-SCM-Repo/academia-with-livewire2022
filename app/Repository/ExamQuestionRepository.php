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

    public function builderModelRepository()
    {
        return (object) [
            // 'id' => null,
            'examen_id' => null,            // exam_id
            'curso_id' => null,             // course_id
            'numero_pregunta' => null,      // question_number
            'puntaje' => null,              // score
            'pregunta_correctas' => null,   // correct_answer
        ];
    }

    public function generarRegistros( int $examen_id ){
        $examen = $this->_examenRepository::find($examen_id);
        if(!$examen) throw new NotFoundResourceException('Error, no se encontró el examen');

        if(!count($examen->questions)>0) self::eliminarRegistros($examen_id);

        $cursos = $examen->course_scores;
        if(count($cursos)==0) throw new Exception('No se encontro informacion de los cursos');

        $preguntas = array();
        $numeroPregunta = 0;
        foreach($cursos as $curso)
            for ($i=0; $i < $curso->number_questions ; $i++) {
                $numeroPregunta++;
                $preguntas[] = [
                    'exam_id' => $examen->id,
                    'course_id' => $curso->id,
                    'question_number' => $numeroPregunta,
                    'score' => null,
                    'correct_answer' => null,
                ];
            }
        return ExamQuestion::insert($preguntas);
    }

    public function actualizarPregunta( int $pregunta_id, object $modelPregunta ){
        $pregunta = self::find($pregunta_id);
        if(!$pregunta) throw new NotFoundResourceException('Error, no se encontró a la pregunta');

        $pregunta->score = $modelPregunta->puntaje;
        $pregunta->correct_answer = $modelPregunta->pregunta_correctas;
        $pregunta->save();
        return $pregunta;
    }

    public function eliminarRegistros( int $examen_id ){
        $examen = $this->_examenRepository::find($examen_id);
        if(!$examen) throw new NotFoundResourceException('Error, no se encontró el examen');

        $preguntas = $examen->questions;
        if(count($preguntas)>0)
            foreach ($preguntas as $pregunta)
                $pregunta->delete();
        return true;
    }
}
