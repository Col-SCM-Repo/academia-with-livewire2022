<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use App\Repository\ExamQuestionRepository;
use Livewire\Component;

class ConfiguracionRespuestas extends Component
{
    public  $examen_id;
    public  $listaPreguntasExamen;
    private $_preguntasExamenRepository;

    public function __construct()
    {
        $this->_preguntasExamenRepository = new ExamQuestionRepository();
    }

    protected $rules = [
        'listaPreguntasExamen' => 'required|array',
        'listaPreguntasExamen.*.question_number' => 'required|integer|min:1',
        'listaPreguntasExamen.*.score' => 'required|numeric|min:1',
        'listaPreguntasExamen.*.correct_answer' => 'required|string|in:A,B,C,D,E',
    ];

    protected $listeners = [
        'renderizar' => 'renderizar',
        'resetear-configuracion-respuestas' => 'initialState'
    ];

    public function initialState(){
        $this->reset(['examen_id']);
    }

    public function render()
    {
        toastAlert($this, 'Renderizando partials.configuracion-respuestas', 'warning');
        return view('livewire.evaluacion.partials.configuracion-respuestas');
    }


    public function renderizar( $examen_id ){
        $this->examen_id = $examen_id;
        $this->listaPreguntasExamen = $this->_preguntasExamenRepository::where('exam_id', $examen_id)->orderBy('question_number')->get();

    }
}
