<?php

namespace App\Http\Controllers;

use App\Enums\EstadosEnum;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Session;

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

    /********************** Modulo de aulas **********************/
    public function aulasIndexView()
    {
        $periodo = null;
        if(Session::has( 'periodo' ))
            $periodo = Session::get('periodo');
        return view('modulos.aulas.index', compact('periodo'));
    }

    public function aulasInformacionView (int $aula_id)
    {
        return view('modulos.aulas.informacion')->with('aula_id', $aula_id);
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
