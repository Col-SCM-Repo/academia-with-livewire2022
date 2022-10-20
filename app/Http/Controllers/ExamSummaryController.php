<?php

namespace App\Http\Controllers;

use App\Repository\ClassroomRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\ExamRepository;
use App\Repository\ExamSummaryRepository;
use Exception;
use Illuminate\Http\Request;

class ExamSummaryController extends Controller
{
    private $_examenResumenRepository, $_classroomRepository, $_examenRepository, $_matriculaRepository ;

    public function __construct()
    {
        $this->_examenResumenRepository = new ExamSummaryRepository() ;
        $this->_classroomRepository = new ClassroomRepository();
        $this->_examenRepository = new ExamRepository();
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function generarDetalleExamenes( string $data_json ){
        $data = json_decode(urldecode($data_json));
        $nombreExamen='';

        $matriculas = [];
        $aula = $this->_classroomRepository::find( $data->aula_ID );
        $buildMatriculaDetail = fn($matricula, $aula, $examenes, $mostrarTodos) =>
            (object)[
                "nombre_estudiante" =>  $matricula->student->entity->full_name() ,
                "nombre_apoderado" =>  count($matricula->student->relative) > 0 ? ($matricula->student->relative[0]->entity? $matricula->student->relative[0]->entity->full_name():'') : 'APODERADO' ,
                'aula' => $aula->name,
                'nivel' => $aula->level->level_type->description,
                'examenes' => $examenes,
                'mostrarTodos' => $mostrarTodos,
            ];

        switch ($data->generacion) {
            case 'masivo':
                $examenesBuscar = $this->_examenRepository::whereIn('id', (array)$data->examen_IDs)->get();
                if(!count($examenesBuscar)>0) throw new Exception('Error, no se encontrarón los examenes indicados. ');

                foreach ($aula->enrollments as $matricula) {
                    $examenes = $this->_examenResumenRepository::where('enrollment_id', $matricula->id)
                                                                ->whereIn('exam_id', (array) $data->examen_IDs)
                                                                ->get();
                    $matriculas[] = $buildMatriculaDetail( $matricula, $aula, $examenes, $data->todosLosAlumnos );
                }
                $nombreExamen='REPORTE EVALUACIÓN AULA '.$aula->name.' - NIVEL '.$aula->level->level_type->description;
                break;
            case 'especifico':
                $matricula = $this->_matriculaRepository::find( $data->matricula_ID );
                if(!$matricula) throw new Exception('Error, no se encontró la matricula. ');

                $examenes = $this->_examenResumenRepository::where('enrollment_id', $matricula->id)
                                                            ->whereIn('exam_id', (array) $data->examen_IDs)
                                                            ->get();
                $matriculas[] = $buildMatriculaDetail( $matricula, $aula, $examenes, false );
                $nombreExamen='REPORTE EVALUACIÓN - '.strtoupper($matricula->student->entity->full_name());
                break;
        }
        return (new PdfController() )->reporteNotasDetalladoPorAlumnos($matriculas, $nombreExamen);
    }

    public function generarDetalleExamenesAula( int $examen_id, int $aula_id ){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $data = $this->_examenResumenRepository::join('enrollments', 'enrollments.id', 'exam_summaries.enrollment_id')
                                                ->where('exam_summaries.exam_id', $examen_id)
                                                ->where('enrollments.id', '!=', null)
                                                ->where('enrollments.classroom_id',$aula_id)
                                                ->get();
        if(count($data)>0){
            $aula = $data[0]->enrollment->classroom;
            $nombreExamen = $data[0]->exam->name.'- AULA '.strtoupper($aula->name) ;
        }
        else
            $nombreExamen = 'EXAMEN NO ENCONTRADO';

        return (new PdfController() )->reporteNotasDetalladoPorAlumnos($data, $nombreExamen);
    }

    public function generarCartasExamen( int $classroom_id ){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $aula = $this->_classroomRepository::find($classroom_id);

        return (new PdfController() )->generarCartasPorAula( $aula->enrollments , 'CARTAS AULA '.$aula->name);
    }

}
