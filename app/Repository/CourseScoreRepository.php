<?php

namespace App\Repository;

use Exception;
use App\Models\CourseScore;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CourseScoreRepository extends CourseScore
{
    private $_examenRepository;
    public function __construct()
    {
        $this->_examenRepository = new ExamRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            'id' => null,
            'examen_id' => null,            // exam_id
            'curso_id' => null,             // course_id
            'puntaje_correctas' => null,    // score_correct
            'puntaje_incorrectas' => null,  // score_wrong
            'numero_preguntas' => null,     // number_questions
            'orden' => null,                // order
        ];
    }

    public function registrar ( array $arrayDatosCurso ) {
        if(count($arrayDatosCurso)==0) throw new Exception('Error, no se encontrò cursos para el examen');

        $examen = $this->_examenRepository::find($arrayDatosCurso[0]->examen_id);
        if(!$examen) throw new NotFoundResourceException('Error, no se encontró el examen ');
        if(count($examen->course_scores)>0 ) throw new Exception('El detalle de examen ya se encuentra registrada, por favor actualice para modificar');

        $cursosExamen= array();
        foreach ($arrayDatosCurso as $index=>$modelCursoDetalle)
            $cursosExamen[] = [
                'exam_id' =>$modelCursoDetalle->examen_id,
                'course_id' =>$modelCursoDetalle->curso_id,
                'score_correct' =>$modelCursoDetalle->puntaje_correctas,
                'score_wrong' =>$modelCursoDetalle->puntaje_incorrectas,
                'number_questions' =>$modelCursoDetalle->numero_preguntas,
                'order' =>$index+1,
            ];
        return CourseScore::insert($cursosExamen);
    }

    public function actualizar ( int $datosCurso_id, object $modelDatosCurso ) {
        $datosCurso = self::find($datosCurso_id);
        if(!$datosCurso) throw new NotFoundResourceException('Error, no se encontró el curso a actualizar');

        $datosCurso->score_correct = $modelDatosCurso->puntaje_correctas ? $modelDatosCurso->puntaje_correctas : $datosCurso->score_correct;
        $datosCurso->score_wrong = $modelDatosCurso->puntaje_incorrectas ? $modelDatosCurso->puntaje_incorrectas : $datosCurso->score_wrong;
        $datosCurso->number_questions = $modelDatosCurso->numero_preguntas ? $modelDatosCurso->numero_preguntas : $datosCurso->number_questions;
        $datosCurso->save();

        return $datosCurso;
    }

    public function eliminarRegistros( int $examen_id ){
        $examen = $this->_examenRepository::find($examen_id);
        if(!$examen) throw new NotFoundResourceException('Error, no se encontró el examen');

        $examen_cursos = $examen->course_scores;
        if(count($examen_cursos)>0)
            foreach($examen_cursos as $curso)
                $curso->delete();
        return true;
    }

    /* public function eliminar ( int $datosCurso_id ) {
        $datosCurso = self::find($datosCurso_id);
        if(!$datosCurso) throw new NotFoundResourceException('Error, no se encontró el curso a eliminar');
        $datosCurso->delete();
        return true;
    } */

}
