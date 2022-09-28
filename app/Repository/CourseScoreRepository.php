<?php

namespace App\Repository;

use Exception;
use App\Models\CourseScore;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CourseScoreRepository extends CourseScore
{
    private $_examenRepository;
    public function __construct() {
        $this->_examenRepository = new ExamRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
           /*  'id' => null, */
            'examen_id' => null,    // exam_id
            'cursos' => array(),    // array CursoExam
        ];
    }

    public function buildCursoExam( int $cursoId, int $orden, int $numeroPreguntas, int $puntajeCorrectas, int $puntajeIncorrectas  ){
        return (object) [
            'curso_id' => $cursoId,                         // course_id
            'orden' => $orden,                              // order
            'numero_preguntas' => $numeroPreguntas,         // number_questions
            'puntaje_correctas' => $puntajeCorrectas,       // score_correct
            'puntaje_incorrectas' => $puntajeIncorrectas,   // score_wrong
        ];
    }

    public function registrar ( object $modelDatosCurso ) {
        $examen = $this->_examenRepository::find($modelDatosCurso->examen_id);

        if(count($modelDatosCurso->cursos)==0 || !$examen  )
            throw new BadRequestException('Error, parametros incorrectos para la configuracion de cursos.');
        if(count($examen->course_scores)>0 )
            throw new Exception('El detalle de examen ya se encuentra registrada, por favor actualice para modificar');

        $cursosExamen= array();
        foreach ($modelDatosCurso->cursos  as $curso)
            $cursosExamen[] = [
                'exam_id' => $modelDatosCurso->examen_id,
                'course_id' => $curso->curso_id,
                'order' => $curso->orden,
                'number_questions' => $curso->numero_preguntas,
                'score_correct' => $curso->puntaje_correctas,
                'score_wrong' => $curso->puntaje_incorrectas,
            ];
        return CourseScore::insert($cursosExamen);
    }

    public function actualizar ( object $modelDatosCurso ) {
        $examen = $this->_examenRepository::find($modelDatosCurso->examen_id);
        if( !$examen )  throw new BadRequestException('Error, no se encontró examen.');

        if(count($examen->course_scores)>0)
            foreach( $examen->course_scores as $cursoPuntajes ) $cursoPuntajes->delete();

        self::registrar($modelDatosCurso);

        if(count($examen->questions)>0){
            $cursos = $examen->course_scores;
            $orden =0;
            $limite=1 + $cursos[$orden]->number_questions ;

            foreach ($examen->questions as $pregunta) {
                if( $pregunta->question_number >= $limite ) {
                    $orden++;
                    $limite+=$cursos[$orden]->number_questions ;
                }
                $pregunta->course_id = $cursos[$orden]->course_id ;
                $pregunta->score = $cursos[$orden]->course_id;
                /* $pregunta->correct_answer = $cursos[$orden]->score_correct; */
                $pregunta->save();
            }
        }
        return true;
    }

    /* public function eliminarRegistrosDetalle( int $examen_id ){
        $examen = $this->_examenRepository::find($examen_id);
        if(!$examen) throw new NotFoundResourceException('Error, no se encontró el examen');

        $examen_cursos = $examen->course_scores;
        if(count($examen_cursos)>0)
            foreach($examen_cursos as $curso)
                $curso->delete();
        return true;
    } */

    /* public function eliminar ( int $datosCurso_id ) {
        $datosCurso = self::find($datosCurso_id);
        if(!$datosCurso) throw new NotFoundResourceException('Error, no se encontró el curso a eliminar');
        $datosCurso->delete();
        return true;
    } */

}
