<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use App\Repository\CourseRepository;
use App\Repository\CourseScoreRepository;
use Livewire\Component;

class ConfiguracionCursos extends Component
{
    public  $examen_id;
    public  $cursosSeleccionados, $cursosDetalle;

    private $_cursosRepository, $_cursosPuntajesRepository;

    public  $rules = [
        'examen_id'     => 'required|integer|min:0',
        'cursosDetalle' => 'required|array',
        'cursosDetalle.*.curso_id'          => 'required|integer|min:0',
        'cursosDetalle.*.numero_preguntas'  => 'required|integer|min:0',
        'cursosDetalle.*.puntaje_correcto'  => 'required|integer|min:0',
        'cursosDetalle.*.puntaje_incorrecto'=> 'required|integer|min:0',
    ];

    protected $listeners = [
        'renderizar' => 'renderizar',
        /*
        'resetear-configuracion-cursos'
        */
    ];

    public function initialState(){
        $this->reset(['cursosSeleccionados', 'cursosDetalle']);
    }


    public function __construct(){
        $this->_cursosRepository = new CourseRepository();
        $this->_cursosPuntajesRepository = new CourseScoreRepository();
    }

    public function mount(){
        self::initialState();
        self::cargarChecks();
    }

    public function render(){
        toastAlert($this, 'Renderizando partials.configuracion-cursos', 'warning');
        return view('livewire.evaluacion.partials.configuracion-cursos');
    }

    public function updatedCursosSeleccionados($valor){
        toastAlert($this, 'Actualizando cursos seleccionados');
    }

    public function cargarChecks ( bool $isEmpty = true ){
        $checks =  [];
        $cursosDisponibles = $this->_cursosRepository::all();

        if($isEmpty){
            foreach($cursosDisponibles as $index=>$curso)
                $checks [$index]= self::buildCursoCheck($curso);
            $this->cursosDetalle = array();
        }
        else{
            // cargar checks almacenados

        }
        $this->cursosSeleccionados = $checks;
    }

    private function buildCursoCheck( object $curso, bool $active=false ){
        return [
            'curso_id' => $curso->id,
            'curso_nombre' => $curso->name,
            'curso_check' => $active,
        ];
    }



    public function renderizar( $examen_id ){
        $this->examen_id = $examen_id;

    }



}
