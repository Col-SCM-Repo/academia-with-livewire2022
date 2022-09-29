<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use App\Repository\CourseRepository;
use App\Repository\CourseScoreRepository;
use App\Repository\ExamRepository;
use Livewire\Component;

class ConfiguracionCursos extends Component
{
    public  $examen_id;
    public  $cursosSeleccionados, $cursosDetalle;

    // Informacion del examen
    public  $numeroCursosAlmacenados, $numPreguntasEstablecidas, $numeroPreguntasConfiguradas, $puntajeTotal;

    private $_cursosRepository, $_cursosPuntajesRepository, $_examenRepository;

    public  $rules = [
        'examen_id'                         => 'required|integer|min:0',
        'cursosDetalle'                     => 'required|array',
        'cursosDetalle.*.orden'             => 'required|integer|min:1',
        'cursosDetalle.*.curso_id'          => 'required|integer|min:1',
        'cursosDetalle.*.nombre_curso'      => 'required|string',
        'cursosDetalle.*.numero_preguntas'  => 'required|integer|min:1',
        'cursosDetalle.*.puntaje_correcto'  => 'required|numeric|min:1',
        /* 'cursosDetalle.*.puntaje_incorrecto'=> 'required|integer|min:0', */
    ];

    protected $listeners = [
        'renderizar' => 'renderizar',
        /*
        'resetear-configuracion-cursos'
        */
    ];

    public function initialState(){
        $this->reset(['cursosSeleccionados', 'cursosDetalle']);
        $this->reset(['numeroCursosAlmacenados', 'numPreguntasEstablecidas', 'numeroPreguntasConfiguradas', 'puntajeTotal' ]);

        $this->numeroCursosAlmacenados = 0 ;
        $this->numPreguntasEstablecidas = 0;
        $this->numeroPreguntasConfiguradas = 0;
        $this->puntajeTotal = 0;
    }

    public function __construct(){
        $this->_cursosRepository = new CourseRepository();
        $this->_cursosPuntajesRepository = new CourseScoreRepository();
        $this->_examenRepository = new ExamRepository();
    }

    public function mount(){
        self::initialState();
        self::cargarChecks();
    }

    public function render(){
        toastAlert($this, 'Renderizando partials.configuracion-cursos', 'warning');
        return view('livewire.evaluacion.partials.configuracion-cursos');
    }

    // Funciones crud
    public function create () {
        $this->validate();

        dd('Creando configuración');
        /*

            $this->numeroCursosAlmacenados = 0 ;    // ******** actualizar objigatoriamente ***************
            $this->numPreguntasEstablecidas = 0;
            $this->numeroPreguntasConfiguradas = 0;
            $this->puntajeTotal = 0;
        */

    }

    public function update () {
        $this->validate();

        dd('Actualizando configuración');
        /*
            $this->numeroCursosAlmacenados = 0 ;    // ******** actualizar objigatoriamente ***************
            $this->numPreguntasEstablecidas = 0;
            $this->numeroPreguntasConfiguradas = 0;
            $this->puntajeTotal = 0;
        */
    }

    // Listeners
    public function updatedCursosSeleccionados($valor){ // Se ejecuta cuando cambia el estado del check
        $casillerosSeleccionados = self::getCursosSeleccionados();
        $cursosDetalleTemp = array_map( fn($a)=> $a['nombre_curso'], $this->cursosDetalle );

        if($valor){ // se aniado un curso
            $nuevos = array_filter( $casillerosSeleccionados, function( $curso ) use ($cursosDetalleTemp) {
                return ! in_array($curso['curso_nombre_corto'], $cursosDetalleTemp);
            });
            $ultimoIndice =$this->cursosDetalle[count($this->cursosDetalle)-1]['orden']+1;
            foreach ($nuevos as $cursoNuevo) {
                $this->cursosDetalle[] = self::buildCursoDetalle($cursoNuevo['curso_id'], $cursoNuevo['curso_nombre_corto'], $ultimoIndice);
            }
        }
        else{ //Si se elimino un curso

            // Pediente -----------------------------------------------
        }

        toastAlert($this, 'Actualizando cursos seleccionados'.($valor? 'true':'false'));

        /* if( count($this->cursosDetalle)>0 ){

        }
        else{

        } */
        $cursosDetalle = array();

        foreach (self::getCursosSeleccionados() as $index => $curso) {
            $cursosDetalle[$index ] = self::buildCursoDetalle($curso['curso_id'], $curso['curso_nombre_corto'], $index+1);
        }
        $this->cursosDetalle = $cursosDetalle;
    }

    public function renderizar( int $examen_id ){
        self::initialState();
        $this->examen_id = $examen_id;

        $cursosAlmacenados = $this->_cursosPuntajesRepository::where('exam_id', $examen_id)->get();

        if(count($cursosAlmacenados)>0){
            $examen = $this->_examenRepository::find($examen_id);
            self::cargarChecks($cursosAlmacenados);   // Cargar checks guardados

            $this->numeroCursosAlmacenados = count($cursosAlmacenados) ;
            $this->numPreguntasEstablecidas = $examen->number_questions;
            $this->numeroPreguntasConfiguradas = count($examen->questions);
            $this->puntajeTotal = $examen->maximun_score ;
        }
        else{
            self::cargarChecks();
        }

    }

    public function onBtnUp(int $index_array){
        $orden = (int)$this->cursosDetalle[$index_array]['orden'];
        $this->cursosDetalle[$index_array]['orden'] = $orden -1 ;
        $this->cursosDetalle[$index_array-1]['orden'] = $orden;
        usort($this->cursosDetalle, function($a, $b){
            if ($a['orden'] == $b['orden']) return 0;
            return ($a['orden'] < $b['orden']) ? -1 : 1;
        });
    }

    public function onBtnDown(int $index_array){
        $orden = (int)$this->cursosDetalle[$index_array]['orden'];
        $this->cursosDetalle[$index_array]['orden'] = $orden +1 ;
        $this->cursosDetalle[$index_array+1]['orden'] = $orden ;
        usort($this->cursosDetalle, function($a, $b){
            if ($a['orden'] == $b['orden']) return 0;
            return ($a['orden'] < $b['orden']) ? -1 : 1;
        });
    }

    // Funciones internas
    private function cargarChecks ( $cursosSeleccionados = null ){
        $checks =  [];
        $cursosDisponibles = $this->_cursosRepository::all();

        if( ! $cursosSeleccionados ){
            foreach($cursosDisponibles as $index=>$curso)
                $checks [$index]= self::buildCursoCheck($curso);
            $this->cursosDetalle = array();
        }
        else{
            // cargar checks almacenados de la db
            // cargar datos del detalleCurso

        }
        $this->cursosSeleccionados = $checks;
    }

    private function buildCursoCheck( object $curso, bool $active=false ){
        return [
            'curso_id' => $curso->id,
            'curso_nombre' => $curso->name,
            'curso_nombre_corto' => $curso->shortname,
            'curso_check' => $active,
        ];
    }

    private function buildCursoDetalle( int $curso_id, string $nombre_curso, int $orden  ){
        return [
            'orden' => $orden,
            'curso_id' => $curso_id ,
            'nombre_curso' => $nombre_curso ,
            'numero_preguntas' => 1 ,
            'puntaje_correcto' => 0 ,
        ];
    }

    private function getCursosSeleccionados ( ){
        $seleccionados = array();
        foreach ($this->cursosSeleccionados as $curso)
            if( $curso['curso_check'] ) $seleccionados[] = $curso;
        return $seleccionados;
    }





}


/*
eliminar
    ** condicional
    P. (I|M) => probabilidad que le guste el ingles, dado que le gustan las matematicas     = numIngles/(pertenece al conjunto de matematicas (universo))
                                                                                            = interseccion / subconjunto


*/

