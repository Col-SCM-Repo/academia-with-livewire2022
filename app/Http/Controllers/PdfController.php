<?php

namespace App\Http\Controllers;

use App\Repository\ExamRepository;
use Illuminate\Http\Request;
use PDF;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PdfController extends Controller
{

    private $_examenRepository;
    public function __construct()
    {
        $this->_examenRepository = new ExamRepository();
    }


    /***************************** Reportes: evaluaciones *****************************/
    public function reporteNotasGeneralesPorExamen( int $examen_id  ){
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


}
