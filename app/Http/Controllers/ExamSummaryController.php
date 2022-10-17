<?php

namespace App\Http\Controllers;

use App\Repository\ClassroomRepository;
use App\Repository\ExamSummaryRepository;
use Illuminate\Http\Request;

class ExamSummaryController extends Controller
{
    private $_examenResumenRepository, $_classroomRepository ;

    public function __construct()
    {
        $this->_examenResumenRepository = new ExamSummaryRepository() ;
        $this->_classroomRepository = new ClassroomRepository();
    }

    public function generarDetalleExamenes( string $json_examen_resumen_ids ){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $examenesResumenIds = json_decode( $json_examen_resumen_ids );
        $data =  $this->_examenResumenRepository::whereIn("id", $examenesResumenIds)->get();

        if(count($data)>0){
            $estudiante = $data[0]->enrollment->student;
            $nombreEstudiante = $estudiante->entity->father_lastname.' '.$estudiante->entity->mother_lastname.' '.$estudiante->entity->name;
            $nombreExamen = $data[0]->exam->name.'-'.strtoupper($nombreEstudiante) ;
        }
        else
            $nombreExamen = 'EXAMEN NO ENCONTRADO';
        return (new PdfController() )->reporteNotasDetalladoPorAlumnos($data, $nombreExamen);
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
