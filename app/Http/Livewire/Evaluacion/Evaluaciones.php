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

    public function __construct()
    {
        $this->_examenRepository = new ExamRepository();
    }

    public function render()
    {
        $listaExamenes = [];
        if(Session::has('periodo'))
         {
             $listaExamenes = $this->_examenRepository::where('period_id', Session::get('periodo')->id);
         }
        return view('livewire.evaluacion.evaluaciones', compact('listaExamenes'));
    }

    public function openModalNuevoExamen(){
        openModal($this, "#form-modal-examenes");
        // Inicializar formularios
        $this->emitTo('evaluacion.partials.configuracion-basica', 'resetear-configuracion-basica');
        $this->emitTo('evaluacion.partials.configuracion-cursos', 'resetear-configuracion-cursos');
        $this->emitTo('evaluacion.partials.configuracion-respuestas', 'resetear-configuracion-respuestas');
    }


    // Primero crear un examen (exams)
    // Segundo - definir la estructura del examen (numero de preguntas y ordenes) (course scores)
    // Tercero - generar preguntas de examen (examen questions)
    // Cuarto

}
