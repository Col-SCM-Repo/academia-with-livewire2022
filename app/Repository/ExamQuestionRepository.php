<?php

namespace App\Repository;

use App\Enums\TiposEstudianteEnum;
use App\Models\ExamQuestion;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ExamQuestionRepository extends ExamQuestion
{
    private $_examenRepository;
    private $_estudianteCodigosExamnRepository, $_examenResumenRepository, $_cursoResumenRepository ;

    public function __construct()
    {
        $this->_examenRepository = new ExamRepository();
        $this->_examenResumenRepository = new ExamSummaryRepository();
        $this->_estudianteCodigosExamnRepository = new StudentExamCodesRepository();
        $this->_cursoResumenRepository = new CourseSummaryRepository();
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

        $buildPregunta  = fn(int  $id, int $num, $rpta='' ) => [ "id"=>$id, "numero" => $num, "respuesta" => $rpta ];
        $buildCurso = fn($id,$nombre,$shortName,$ptn, $preguntas) => ["id" => $id,
                                                                "nombre" =>$nombre,
                                                                "nombre_corto" => $shortName,
                                                                "numero" => count($preguntas),
                                                                "puntaje" => $ptn,
                                                                "preguntas" => $preguntas
                                                            ];

        $ultimo_curso_id = null ;
        $ultimo_puntaje = 0;
        foreach (self::where('exam_id', $examen_id)->orderBy('question_number')->get() as $index => $pregunta){
            if($cursoActual != $pregunta->course_id){   // revisar por que esta almacenando el couseid en score
                if( $cursoActual>=0 ) $cursos [$index] = $buildCurso($ultimo_curso_id, $nombreCurso, $nombreCursoCorto, $ultimo_puntaje, $preguntas );
                $preguntas = array();
                $preguntas[] = $buildPregunta($pregunta->id, $pregunta->question_number, $pregunta->correct_answer);
                $cursoActual = $pregunta->course_id;
                $nombreCurso = $pregunta->course->name;
                $nombreCursoCorto = $pregunta->course->shortname;
            }
            else $preguntas[] = $buildPregunta($pregunta->id, $pregunta->question_number, $pregunta->correct_answer);
            $ultimo_curso_id = $pregunta->course_id;
            $ultimo_puntaje = $pregunta->score;
        }
        if($ultimo_curso_id) $cursos [] = $buildCurso($ultimo_curso_id, $nombreCurso, $nombreCursoCorto,$ultimo_puntaje,  $preguntas);
        return $cursos;
    }

    public function corregirExamen( int $examen_id, array $respuestas_data ){
        $solucionarioPreguntas = self::getPreguntasExamen($examen_id);
        $examen = $this->_examenRepository::find($examen_id);
        if(!count($respuestas_data)>0) throw new Exception('No se encontro hojas de respuesta');
        if(!$examen) throw new Exception('Examen no encontrado');
        $numeroExamnCorregido = 0;
        $numeroExamnError = 0;
        $erroresMsg = array();
        foreach ( $respuestas_data as $i=>$respuestaData) {
            $numeroCorrectas = 0;
            $numeroIncorrectas = 0;
            $numeroBlanco = 0;
            $ptnCorrectas = 0;
            $ptnIncorrectas = 0;

            $examen_resumen_id = null;
            try {
                $estudianteExamenCodigo = $this->_estudianteCodigosExamnRepository->buscarEstudianteExamnPorCodigo( $respuestaData['cod_alumno'] );
                if( ! $estudianteExamenCodigo ) throw new Exception('Error, no se encontro el estudiante con codigo '.$respuestaData['cod_alumno']);

                $moExamenResumen = $this->_examenResumenRepository->builderModelRepository();
                $moExamenResumen->examen_id = $examen_id;
                $moExamenResumen->matricula_id = $estudianteExamenCodigo->enrollment_id ;
                $moExamenResumen->codigo_examen = $respuestaData['cod_grupo'] ;
                $moExamenResumen->codigo_estudiante = $estudianteExamenCodigo->enrollment_code ;
                $moExamenResumen->tipo_estudiante = $estudianteExamenCodigo->enrollment_id? TiposEstudianteEnum::ESTUDIANTE : TiposEstudianteEnum::LIBRE;
                $moExamenResumen->apellidos = $estudianteExamenCodigo->surname;
                $moExamenResumen->nombre = $estudianteExamenCodigo->name;
                $examen_resumen_id = $this->_examenResumenRepository->registrar($moExamenResumen)->id;
                foreach ($solucionarioPreguntas as $cursoSolucionario){
                    $cursoResumen = self::corregirPreguntasPorCurso( $respuestaData['respuestas'], $cursoSolucionario, $examen->score_wrong, $examen_resumen_id );
                    $numeroCorrectas += $cursoResumen->correct_answers ;
                    $numeroIncorrectas += $cursoResumen->wrong_answers ;
                    $numeroBlanco += $cursoResumen->blank_answers ;
                    $ptnCorrectas += $cursoResumen->correct_score;
                    $ptnIncorrectas += $cursoResumen->wrong_score;
                }
                $this->_examenResumenRepository->actualizarPuntajeResumen($examen_resumen_id,$numeroCorrectas,$numeroIncorrectas,$numeroBlanco,$ptnCorrectas,$ptnIncorrectas);
                $numeroExamnCorregido ++;
            } catch (Exception $err) {
                if($examen_resumen_id) $this->_examenResumenRepository->eliminarExamenResumen($examen_resumen_id);
                $erroresMsg []= 'Cartilla['.($i+1).']: '. ($err->getMessage());
                $numeroExamnError++;
                continue;
            }
        }
        return (object) [ 'examenesCorregidos'=>$numeroExamnCorregido, 'examenesConErrores'=>$numeroExamnError, 'logs'=>$erroresMsg ];
    }

    /* private function eliminarCursosResumen( $examen_resumen_id ){
        // pendiente ....
    }
 */

    private function corregirPreguntasPorCurso( $respuestasAlumno, $solucionario, $valor_incorrectas, $examen_resumen_id ){
        $numeroPreguntasCorrectas = 0;
        $numeroPreguntasIncorrectas = 0;
        $numeroPreguntasBlanco = 0;
        $respuestasAlumnoStr = '';

        foreach ($solucionario['preguntas'] as $pregunta) {
            $respuestaAlumno = $respuestasAlumno[ ((int)$pregunta['numero'])-1 ];
            $respuestasAlumnoStr .= $respuestaAlumno ;
            if($respuestaAlumno != '' && $respuestaAlumno != ' ' && $respuestaAlumno != null )
                if( $pregunta['respuesta'] == $respuestaAlumno ) $numeroPreguntasCorrectas ++;
                else $numeroPreguntasIncorrectas ++;
            else $numeroPreguntasBlanco++;
        }
        $moResumenCurso = $this->_cursoResumenRepository->builderModelRepository() ;
        $moResumenCurso->resumen_examen_id = $examen_resumen_id ;
        $moResumenCurso->curso_id = $solucionario['id'];
        $moResumenCurso->numero_correctas = $numeroPreguntasCorrectas;
        $moResumenCurso->numero_incorrectas = $numeroPreguntasIncorrectas;
        $moResumenCurso->numero_blanco = $numeroPreguntasBlanco;
        $moResumenCurso->respuestas_estudiante = $respuestasAlumnoStr;
        $moResumenCurso->puntaje_correctos = $numeroPreguntasCorrectas * $solucionario['puntaje'];
        $moResumenCurso->puntaje_incorrectos = $valor_incorrectas * $numeroPreguntasIncorrectas;

        return $this->_cursoResumenRepository->registrar($moResumenCurso);
    }

    /* public function preguntasExamen( int $examen_id ){
        $respuestasExamen = [];
        foreach ( self::where('exam_id', $examen_id)->orderBy('question_number', 'asc')->get()  as $index => $pregunta){
            if( $index +1  != $pregunta->question_number  )  throw new Exception('Error, faltan preguntas configuradas, numero '.($index+1));
            if( $pregunta->correct_answer != null)  throw new Exception('Error, aun hay preguntas sin configurar (en blanco), numero '.($index+1));

            $respuestasExamen[] = (object)[ 'numero'=>$pregunta->question_number,
                                            'respuesta'=>$pregunta->correct_answer,
                                            'valor'=>$pregunta->score ];
        }
        return $respuestasExamen;
    } */

}
