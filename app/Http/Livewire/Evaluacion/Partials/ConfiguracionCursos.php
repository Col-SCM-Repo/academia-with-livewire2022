<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use App\Enums\EstadosEntidadEnum;
use App\Repository\CourseRepository;
use App\Repository\CourseScoreRepository;
use App\Repository\ExamRepository;
use Exception;
use Livewire\Component;

/*
    NOTA: Pendiente eliminar score_wrong de cursosScores y examQuestions
*/

class ConfiguracionCursos extends Component
{
    public  $examen_id;
    public  $cursosSeleccionados, $cursosDetalle;

    public  $numeroCursosAlmacenados, $numPreguntasEstablecidas,    // Almacenados en BD
            $numeroPreguntasConfiguradas, $puntajeTotal;            // Temporales

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
        'resetear-configuracion-cursos' => 'initialState',
    ];

    public function initialState(){
        $this->reset(['examen_id']);
        $this->reset(['cursosSeleccionados', 'cursosDetalle']);
        $this->reset(['numeroCursosAlmacenados', 'numPreguntasEstablecidas', 'numeroPreguntasConfiguradas', 'puntajeTotal' ]);

        $this->numeroCursosAlmacenados = 0 ;
        $this->numPreguntasEstablecidas = 0;
        $this->numeroPreguntasConfiguradas = 0;
        $this->puntajeTotal = 0;

        self::cargarChecks();
    }

    public function __construct(){
        $this->_cursosRepository = new CourseRepository();
        $this->_cursosPuntajesRepository = new CourseScoreRepository();
        $this->_examenRepository = new ExamRepository();
    }

    public function mount(){
        self::initialState();
    }

    public function render(){
        toastAlert($this, 'Renderizando partials.configuracion-cursos', 'warning');
        return view('livewire.evaluacion.partials.configuracion-cursos');
    }

    // Funciones crud
    public function create () {
        $this->validate();
        $moPuntuacionCurso = self::buildModelPuntajeCurso();
        try {
             $numeroCursosCreados = $this->_cursosPuntajesRepository->actualizar($moPuntuacionCurso);
             sweetAlert($this, 'curso', EstadosEntidadEnum::CREATED);
             $this->numeroCursosAlmacenados = $numeroCursosCreados ;
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function update () {
        $this->validate();
        $moPuntuacionCurso = self::buildModelPuntajeCurso();
        try {
             /* $numeroCursosCreados =  */$this->_cursosPuntajesRepository->registrar($moPuntuacionCurso);
             sweetAlert($this, 'curso', EstadosEntidadEnum::CREATED);
             /* $this->numeroCursosAlmacenados = $numeroCursosCreados ; */
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }

    }

    // Listeners
    public function updatedCursosSeleccionados($valor){ // Se ejecuta cuando cambia el estado del check
        $casillerosSeleccionados = self::getCursosSeleccionados();

        if($valor){ // se aniado un curso
            $cursosDetalleStr = array_map( fn($a)=> $a['nombre_curso'], $this->cursosDetalle );

            $nuevos = array_filter( $casillerosSeleccionados, function( $curso ) use ($cursosDetalleStr) {
                return ! in_array($curso['curso_nombre_corto'], $cursosDetalleStr);
            });
            $numCursosDetalle = count($this->cursosDetalle);

            foreach ($nuevos as $cursoNuevo)
                $this->cursosDetalle[] = self::buildCursoDetalle($cursoNuevo['curso_id'], $cursoNuevo['curso_nombre_corto'], $numCursosDetalle+1);
        }
        else{ //Si se elimino un curso
            $cursosSeleccionadosStr = array_map( fn($a)=> $a['curso_nombre_corto'], $casillerosSeleccionados );

            $cursosRestantes = array_filter( $this->cursosDetalle, function($cuDetalle) use ($cursosSeleccionadosStr) {
                return in_array($cuDetalle['nombre_curso'], $cursosSeleccionadosStr);
            });

            $nuevoArray = array();
            $index = 1;
            foreach ($cursosRestantes as $cuIterador) {
                $cuIterador['orden'] = $index;
                $nuevoArray[$index-1] = $cuIterador;
                $index++;
            }
            $this->cursosDetalle =  $nuevoArray;
        }
        try {
            self::actualizarInformacionExamen();
        } catch (Exception $err) {
            $this->puntajeTotal = '-';
            /* toastAlert($this, 'Error, no pueden ir campos en blanco'); */
        }
    }

    public function updatedCursosDetalle($value){
        try {
            if($value== null || $value=='' ) throw new Exception();
            self::actualizarInformacionExamen();
        } catch (Exception $err) {
            $this->puntajeTotal = '-';
            toastAlert($this, 'Error, no pueden ir campos en blanco o con 0');
        }
    }

    public function renderizar( int $examen_id ){   // Cargar toda la informacion almacenada *******************
        self::initialState();
        $this->examen_id = $examen_id;

        $cursosAlmacenados = $this->_cursosPuntajesRepository::where('exam_id', $examen_id)->get();

        $examen = $this->_examenRepository::find($examen_id);
        $this->numPreguntasEstablecidas = $examen->number_questions;
        $this->puntajeTotal = $examen->maximun_score? $examen->maximun_score : '-';

        if(count($cursosAlmacenados)>0){
            self::cargarChecks($cursosAlmacenados);   // Cargar checks guardados

            $this->numeroCursosAlmacenados = count($cursosAlmacenados) ;
            $this->numeroPreguntasConfiguradas = count($examen->questions);
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


    private function buildModelPuntajeCurso(){
        $modelCourseScore = $this->_cursosPuntajesRepository->builderModelRepository();

        $modelCourseScore->examen_id = $this->examen_id;
        $modelCourseScore->cursos = array_map( fn($curso) => $this->_cursosPuntajesRepository->buildCursoExam(  $curso['curso_id'],
                                                                                                                $curso['orden'],
                                                                                                                $curso['numero_preguntas'],
                                                                                                                $curso['puntaje_correcto']
                                                                                                              ), $this->cursosDetalle);
        return $modelCourseScore;
    }

    private function getCursosSeleccionados ( ){
        return array_filter( $this->cursosSeleccionados, fn($cuSeleccionado)=>$cuSeleccionado['curso_check'] );
    }

    private function actualizarInformacionExamen(){
        $numeroPreguntasConfiguradas = 0;
        $puntajeTotal = 0;

        foreach ($this->cursosDetalle as $cursoDetalle) {
            if($cursoDetalle['numero_preguntas'] =='' || $cursoDetalle['puntaje_correcto'] =='') throw new Exception();
            $numeroPreguntasConfiguradas += $cursoDetalle['numero_preguntas'];
            $puntajeTotal += $cursoDetalle['numero_preguntas'] * (float)$cursoDetalle['puntaje_correcto'];
        }
        $this->numeroPreguntasConfiguradas = $numeroPreguntasConfiguradas;
        $this->puntajeTotal = $puntajeTotal;
    }




}


/*
eliminar
    ** condicional
    P. (I|M) => probabilidad que le guste el ingles, dado que le gustan las matematicas     = numIngles/(pertenece al conjunto de matematicas (universo))
                                                                                            = interseccion / subconjunto


*/

