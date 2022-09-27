<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use Livewire\Component;

class ConfiguracionBasica extends Component
{
    public $examen_id, $formulario_examen;
    public $lista_grupos, $lista_niveles;
    private $_grupoRepository, $_nivelRepository, $_examenRepository;


    public function __construct()
    {

    }

    protected $listeners = [];
    /*
            'resetear-configuracion-basica'
    */

    protected $rules = [
        'formulario_examen.nombre' => 'required | string',
        'formulario_examen.tipo_examen' => 'required | in:simulacrum, monthly, weekly, daily, quick, other',
        'formulario_examen.nivel_id' => 'nullable | integer | min:1',
        'formulario_examen.grupo_id' => 'required | integer | min:1',
        'formulario_examen.codigo_de_grupo' => 'required | integer | min:0 | max:99',
        'formulario_examen.fecha_examen' => 'required | date',
        'formulario_examen.numero_preguntas' => 'required ',
    ];

    public function initialState(){
        $this->reset(['examen_id', 'formulario_examen']);

        $this->formulario_examen['nombre'] = null;
        $this->formulario_examen['tipo_examen'] = null;
        $this->formulario_examen['nivel_id'] = null;
        $this->formulario_examen['grupo_id'] = null;
        $this->formulario_examen['codigo_de_grupo'] = null;
        $this->formulario_examen['fecha_examen'] = null;
        $this->formulario_examen['numero_preguntas'] = null;
    }

    public function mount(){
        self::initialState();
    }

    public function render()
    {
        return view('livewire.evaluacion.partials.configuracion-basica');
    }

    // Funciones para CRUD ( CU )
    public function crearExamen(){
        $this->validate();
        dd( $this->formulario_examen );

    }

    public function actualizarExamen(){
        $this->validate();
        $this->validate([ 'examen_id' => 'required | integer | min:1' ]);
        dd( $this->formulario_examen, $this->examen_id );

    }

    // Funciones de listener

    // Funciones internas (privadas)

}
