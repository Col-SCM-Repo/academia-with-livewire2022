<?php

namespace App\Http\Livewire\Matricula\Partials;

use Livewire\Component;

class MatriculaConfiguracionPagos extends Component
{

    /*
        'tipo_pago' => 'required|string|in:cash,credit',    // agregar evento
        'cuotas' => 'required|integer|min:0|max:3',         // hacer dinamico
        'lista_Cuotas' => ' array',
        'lista_cuotas.*.costo' => 'numeric | min:0',
        'lista_cuotas.*.fecha' => 'date',
        'costo' => 'required | numeric | min:0',
    */
    public function __construct()
    {

    }

    public function initialState(){

    }

    public function render()
    {
        return view('livewire.matricula.partials.matricula-configuracion-pagos');
    }
}
