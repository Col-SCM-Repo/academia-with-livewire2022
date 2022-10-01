<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Home
    public function index()
    {
        return view('index');
    }

    /********************** Modulo evaluaciones **********************/
    public function configuracion()     //evaluaciones/configuracion-examenes
    {
        return view('modulos.evaluaciones.configuracion');
    }

    public function revision()          //evaluaciones/carga-revision
    {
        return view('modulos.evaluaciones.carga-revision');
    }

    public function reporte()           //evaluaciones/resultados-reporte
    {
        return "evaluaciones - reporte";
    }

    /********************** Modulo mantenimiento **********************/
    public function ciclosAulas() // mantenimiento/ciclos-y-aulas
    {
        return view('modulos.ciclosyAulas.main');
    }

    /********************** Modulo Matricula **********************/
    public function nuevaMatriculaView()
    {
        return view('modulos.matricula.nueva');
    }
    public function informacionAlumnoView()
    {
        return view('modulos.matricula.informacion-alumno');
    }



















    // mantenimiento
    public function mantenimiento()
    {
        return "mantenimiento";
    }

    // Modulo matriculas
    public function matriculas()
    {
        return "matriculas";
    }

    // reportes
    public function reportes()
    {
        return "reportes";
    }
}
