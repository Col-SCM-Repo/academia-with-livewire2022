<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use App\Enums\EstadosAlertasEnum;
use App\Repository\ExamQuestionRepository;
use Exception;
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
        'listaPreguntasExamen'                      => 'required|array',
        'listaPreguntasExamen.*.id'                 => 'required|integer|min:1',
        'listaPreguntasExamen.*.nombre'             => 'required|string|min:1',
        'listaPreguntasExamen.*.nombre_corto'       => 'required|string|min:1',
        'listaPreguntasExamen.*.numero'             => 'required|integer|min:1',
        'listaPreguntasExamen.*.cursos'             => 'required|array',
        'listaPreguntasExamen.*.cursos.*.numero'    => 'required|integer|min:1',
        'listaPreguntasExamen.*.cursos.*.puntaje'   => 'required|numeric|min:1',
        'listaPreguntasExamen.*.cursos.*.respuesta' => 'nullable',
    ];

    protected $listeners = [
        'renderizar' => 'renderizar',
        'resetear-configuracion-respuestas' => 'initialState'
    ];

    public function mount( $examen_id = null ){
        $this->examen_id=$examen_id;
        if($examen_id) self::renderizar($examen_id);
    }

    public function initialState(){
        $this->reset(['examen_id']);
        $this->reset(['listaPreguntasExamen']);
    }

    public function render(){
        toastAlert($this, 'Renderizando partials.configuracion-respuestas', 'warning');
        return view('livewire.evaluacion.partials.configuracion-respuestas');
    }

    public function almacenarNota( int $nota_id, $valor ){
        try {
            if(!in_array($valor, ['A','B','C','D','E'])) $valor=null;
            $pregunta= $this->_preguntasExamenRepository->actualizarPregunta($nota_id, $valor);
            toastAlert($this, 'Respuesta de la pregunta '.($pregunta->question_number).'registrada.', EstadosAlertasEnum::SUCCESS );
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function renderizar( $examen_id ){
        $this->examen_id = $examen_id;
        try {
            $this->listaPreguntasExamen = $this->_preguntasExamenRepository->getPreguntasExamen($examen_id);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
            $this->reset(['listaPreguntasExamen']);
        }
    }
}
