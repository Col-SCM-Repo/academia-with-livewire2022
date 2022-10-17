<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use App\Enums\EstadosEntidadEnum;
use App\Repository\CourseRepository;
use App\Repository\CourseScoreRepository;
use App\Repository\ExamQuestionRepository;
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

    public  $numeroTotalPreguntas, $numeroPreguntasEnBD, $puntajeTotal, $pendienteGuardar;

    private $_cursosRepository, $_cursosPuntajesRepository, $_examenPreguntasRepository, $_examenRepository;

    public  $rules = [
        'examen_id'                         => 'required|integer|min:0',
        'cursosDetalle'                     => 'required|array',
        'cursosDetalle.*.orden'             => 'required|integer|min:1',
        'cursosDetalle.*.curso_id'          => 'required|integer|min:1',
        'cursosDetalle.*.nombre_curso'      => 'required|string',
        'cursosDetalle.*.numero_preguntas'  => 'required|integer|min:1',
        'cursosDetalle.*.puntaje_correcto'  => 'required|numeric|min:1',
        /* 'cursosDetalle.*.puntaje_incorrecto'=> 'required|integer|min:0',
            wire:change="setSomeProperty($event.target.value)"
        */
    ];

    protected $listeners = [
        'renderizar' => 'renderizar',
        'resetear-configuracion-cursos' => 'initialState',
    ];

    public function initialState(){
        $this->reset(['examen_id']);
        $this->reset(['cursosSeleccionados', 'cursosDetalle']);
        $this->reset(['numeroTotalPreguntas', 'numeroPreguntasEnBD', 'puntajeTotal', 'pendienteGuardar' ]);

        $this->numeroTotalPreguntas = 0 ;
        $this->numeroPreguntasEnBD = 0;
        $this->puntajeTotal = 0;
        $this->pendienteGuardar = true;
    }

    public function __construct(){
        $this->_cursosRepository = new CourseRepository();
        $this->_cursosPuntajesRepository = new CourseScoreRepository();
        $this->_examenRepository = new ExamRepository();
        $this->_examenPreguntasRepository = new ExamQuestionRepository();
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
        $moPuntuacionCurso = self::buildModelPuntajeCurso();
        try {
            $this->numeroPreguntasEnBD = $this->_cursosPuntajesRepository->registrar($moPuntuacionCurso);
            sweetAlert($this, 'curso', EstadosEntidadEnum::CREATED);
            $this->emitTo('evaluacion.partials.configuracion-respuestas', 'renderizar', $this->examen_id);
            $this->pendienteGuardar = false;
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function update () {
        $this->validate();
        $moPuntuacionCurso = self::buildModelPuntajeCurso();
        try {
            $this->numeroPreguntasEnBD = $this->_cursosPuntajesRepository->actualizar($moPuntuacionCurso);
             sweetAlert($this, 'curso', EstadosEntidadEnum::UPDATED);
             $this->emitTo('evaluacion.partials.configuracion-respuestas', 'renderizar', $this->examen_id);
             $this->pendienteGuardar = false;
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
        $this->pendienteGuardar = true;
        try {
            self::actualizarInformacionExamen();
        } catch (Exception $err) {
            $this->puntajeTotal = '-';
            /* toastAlert($this, 'Error, no pueden ir campos en blanco'); */
        }
    }

    public function generarNotasExamen( int $examen_id ){
        try {
            $this->_examenPreguntasRepository->generarRegistros( $examen_id );
            sweetAlert($this, 'curso', EstadosEntidadEnum::UPDATED);
            $this->emitTo('evaluacion.partials.configuracion-respuestas', 'renderizar', $examen_id);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function updatedCursosDetalle($value){
        $this->pendienteGuardar = true;
        try {
            if($value== null || $value=='' ) throw new Exception();
            self::actualizarInformacionExamen();
        } catch (Exception $err) {
            toastAlert($this, 'Error, no pueden ir campos en blanco o 0s');
        }
    }

    public function renderizar( int $examen_id ){   // Cargar toda la informacion almacenada *******************
        self::initialState();
        $this->examen_id = $examen_id;

        $cursosAlmacenados = $this->_cursosPuntajesRepository::where('exam_id', $examen_id)->orderBy('order', 'asc')->get();

        $examen = $this->_examenRepository::find($examen_id);

        $this->numeroTotalPreguntas = $examen->number_questions;
        $this->puntajeTotal = $examen->maximun_score;
        $this->numeroPreguntasEnBD = $examen->number_questions? $examen->number_questions : 0;

        if(count($cursosAlmacenados)>0) self::cargarChecks($cursosAlmacenados); // Cargar checks guardados
        else self::cargarChecks();                                              // Cargar checks guardados

        self::actualizarInformacionExamen();
    }

    public function onBtnUp(int $index_array){
        $orden = (int)$this->cursosDetalle[$index_array]['orden'];
        $this->cursosDetalle[$index_array]['orden'] = $orden -1 ;
        $this->cursosDetalle[$index_array-1]['orden'] = $orden;
        usort($this->cursosDetalle, function($a, $b){
            if ($a['orden'] == $b['orden']) return 0;
            return ($a['orden'] < $b['orden']) ? -1 : 1;
        });
        $this->pendienteGuardar = true;

    }

    public function onBtnDown(int $index_array){
        $orden = (int)$this->cursosDetalle[$index_array]['orden'];
        $this->cursosDetalle[$index_array]['orden'] = $orden +1 ;
        $this->cursosDetalle[$index_array+1]['orden'] = $orden ;
        usort($this->cursosDetalle, function($a, $b){
            if ($a['orden'] == $b['orden']) return 0;
            return ($a['orden'] < $b['orden']) ? -1 : 1;
        });

        $this->pendienteGuardar = true;
    }

    // Funciones internas
    private function cargarChecks ( $cursosRegistradosDB = null ){
        $this->pendienteGuardar = true;

        $cursosChecks = array();
        $cursosDetalle = array();

        foreach( $this->_cursosRepository::all() as $index=>$curso)
            $cursosChecks [$index]= self::buildCursoCheck($curso);

        if( $cursosRegistradosDB ){
            $cursosRegistradosDBStr = [];
            foreach ($cursosRegistradosDB as $cursoDB)
                $cursosRegistradosDBStr [] = $cursoDB->course->name;

            $cursosTemp = array();
            foreach ($cursosChecks as $cursoCheck){
                if( in_array($cursoCheck['curso_nombre'], $cursosRegistradosDBStr))
                    $cursoCheck['curso_check'] = true;
                    $cursosTemp[] = $cursoCheck;
            }

            foreach ($cursosRegistradosDB as $index=>$cursoRegistradoDB)
                $cursosDetalle [] = self::buildCursoDetalle($cursoRegistradoDB->course_id,
                                                            $cursoRegistradoDB->course->shortname ,
                                                            $index+1,
                                                            $cursoRegistradoDB->number_questions,
                                                            $cursoRegistradoDB->score_correct);
            $cursosChecks = $cursosTemp;
            $this->pendienteGuardar = false;
        }
        $this->cursosDetalle = $cursosDetalle;
        $this->cursosSeleccionados = $cursosChecks;
    }

    private function buildCursoCheck( object $curso, bool $active=false ){
        return [
            'curso_id' => $curso->id,
            'curso_nombre' => $curso->name,
            'curso_nombre_corto' => $curso->shortname,
            'curso_check' => $active,
        ];
    }

    private function buildCursoDetalle( int $curso_id, string $nombre_curso, int $orden, int $numero_preguntas=1, $puntaje_correcto=1 ){
        return [
            'orden' => $orden,
            'curso_id' => $curso_id ,
            'nombre_curso' => $nombre_curso ,
            'numero_preguntas' => $numero_preguntas ,
            'puntaje_correcto' => $puntaje_correcto ,
        ];
    }


    private function buildModelPuntajeCurso(){
        $modelCourseScore = $this->_cursosPuntajesRepository->builderModelRepository();

        $modelCourseScore->examen_id = $this->examen_id;
        $modelCourseScore->numero_preguntas = ($this->numeroTotalPreguntas && $this->numeroTotalPreguntas != '-' )? $this->numeroTotalPreguntas : null ;
        $modelCourseScore->maximo_puntaje = ($this->puntajeTotal && $this->puntajeTotal != '-' )? $this->puntajeTotal : null ;

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
        toastAlert($this, 'ACTUAÑIZANDO', 'error');
        $numeroTotalPreguntas = 0;
        $puntajeTotal = 0;

        $faltanPuntajes = false;
        $faltanPreguntas = false;

        foreach ($this->cursosDetalle as $cursoDetalle) {
            if( !$faltanPuntajes && $cursoDetalle['numero_preguntas']!=null ) $numeroTotalPreguntas += $cursoDetalle['numero_preguntas'];
            else $faltanPuntajes = true;

            if( !$faltanPreguntas && $cursoDetalle['puntaje_correcto']!=null ) $puntajeTotal += $cursoDetalle['numero_preguntas'] * (float)$cursoDetalle['puntaje_correcto'];
            else $faltanPreguntas = true;

            if( $faltanPuntajes && $faltanPreguntas ) break;
        }
        $this->puntajeTotal = $faltanPuntajes? '-' : $puntajeTotal;
        $this->numeroTotalPreguntas = $faltanPreguntas? '-' : $numeroTotalPreguntas;
    }




}


/*
eliminar
    ** condicional
    P. (I|M) => probabilidad que le guste el ingles, dado que le gustan las matematicas     = numIngles/(pertenece al conjunto de matematicas (universo))
                                                                                            = interseccion / subconjunto


*/

