<?php

namespace App\Http\Livewire\Evaluacion;

use App\Repository\ExamRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Evaluaciones extends Component
{
    // Formulario evaluaciones score_wrong
    public $examen_id;

    private $_examenRepository;

    protected $listeners = [
        'renderizar-componentes' => 'renderizarComponentes'
    ];

    public function __construct()
    {
        $this->_examenRepository = new ExamRepository();
    }

    public function render()
    {
        toastAlert($this, 'Renderizando evaluaciones', 'warning');
        $listaExamenes = [];
        if(Session::has('periodo'))
         {
             $listaExamenes = $this->_examenRepository::where('period_id', Session::get('periodo')->id)->get();
         }
        return view('livewire.evaluacion.evaluaciones', compact('listaExamenes'));
    }

    public function editarExamenOpenModal( int $examen_id ){
        $this->examen_id = $examen_id;
        self::renderizarComponentes($examen_id);
        openModal($this, "#form-modal-examenes");
    }

    public function eliminarExamen( int $examen_id ){
        dd('Eliminando examen id '.$examen_id);

    }

    public function openModalNuevoExamen(){
        openModal($this, "#form-modal-examenes");
        // Inicializar formularios
        $this->emitTo('evaluacion.partials.configuracion-basica', 'resetear-configuracion-basica');
        $this->emitTo('evaluacion.partials.configuracion-cursos', 'resetear-configuracion-cursos');
        $this->emitTo('evaluacion.partials.configuracion-respuestas', 'resetear-configuracion-respuestas');
    }

    public function renderizarComponentes( $examen_id = null ){
        $this->emitTo('evaluacion.partials.configuracion-basica', 'renderizar', $examen_id);
        $this->emitTo('evaluacion.partials.configuracion-cursos', 'renderizar', $examen_id);
        $this->emitTo('evaluacion.partials.configuracion-respuestas', 'renderizar', $examen_id);
    }

    // Primero crear un examen (exams)
    // Segundo - definir la estructura del examen (numero de preguntas y ordenes) (course scores)
    // Tercero - generar preguntas de examen (examen questions)
    // Cuarto

}
