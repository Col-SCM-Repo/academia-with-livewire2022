<?php

namespace App\Http\Controllers;

use App\Models\ExamSummary;
use App\Repository\ClassroomRepository;
use App\Repository\ExamRepository;
use App\Repository\ExamSummaryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PdfController extends Controller
{

    private $_examenRepository, $_examenResumenRepository, $_aulaRepository;

    public function __construct()
    {
        $this->_examenRepository = new ExamRepository();
        $this->_examenResumenRepository = new ExamSummaryRepository();
        $this->_aulaRepository  = new ClassroomRepository();
    }


    /***************************** Reportes: evaluaciones *****************************/
    public function reporteNotasGeneralesPorExamen( int $examen_id/* , $nombreExamen  */){
        $examen = $this->_examenRepository::find( $examen_id );
        if( ! $examen ) throw new NotFoundResourceException('Error, no se encontro el examen.');
/*
        $data = [
            'resumenExamen' => $examen->exam_summaries->toArray(),
            'examen'
        ];
        $resumenExamen = ; */
        // $pdf->download('archivo.pdf');

        return PDF::loadView('reports.pdf.reporte-notas-general', compact('examen'))->stream("RESULTADOS GENERALES DEL EXAMEN  - ". ($examen->name) .'.pdf');
    }

    public function reporteNotasDetalladoPorAlumnos( $examenesResumen, $nombreExamen){
        /* dd($examenesResumenIds); */
        return PDF::loadView('reports.pdf.reporte-notas-detallado', compact(['examenesResumen', 'nombreExamen']))
                    ->setPaper('A4', 'portrait')
                    ->stream("$nombreExamen.pdf");
    }

    public function generarCartasPorAula($matriculas, $nombre_documento){
        /* dd($matriculas, $nombre_documento); */
        return PDF::loadView('reports.pdf.reporte-notas-cartas', compact(['matriculas', 'nombre_documento']))
                    ->setPaper('A4', 'portrait')
                    ->stream("$nombre_documento.pdf");
    }

    public function generarListaAlumnos ( int $classroom_id ) {
        $aula = $this->_aulaRepository::find( $classroom_id );
        if(!$aula) throw new NotFoundHttpException('Error, no se encontro el aula');

        $matriculas = $aula->enrollments;

        $nombre_documento = 'LISTA DE ALUMNOS AULA - '.($aula->name);
        return PDF::loadView('reports.pdf.reporte-lista-alumnos', compact(['matriculas', 'nombre_documento']))
                    ->setPaper('A4', 'portrait')
                    ->stream("$nombre_documento.pdf");
    }

}
