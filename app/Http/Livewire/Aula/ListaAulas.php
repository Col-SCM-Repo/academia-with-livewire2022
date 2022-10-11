<?php

namespace App\Http\Livewire\Aula;

use App\Repository\LevelRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ListaAulas extends Component
{
    public $tipoNivelId, $periodoId;
    public $nivel;

    private $_nivelRepository;

    public function __construct()
    {
        $this->_nivelRepository = new LevelRepository();
    }

    public function mount ( $tipo_nivel_id ){
        $this->tipoNivelId = $tipo_nivel_id;
    }

    public function render()
    {
        if( $this->tipoNivelId && Session::has('periodo') ){
            $this->nivel = $this->_nivelRepository::where( 'type_id',$this->tipoNivelId)->where('period_id',Session::get('periodo')->id)->first();
            /* if($nivel) $this->listaAulas = $nivel->classrooms;
            else $this->reset(['listaAulas']); */
        }else $this->reset(['listaAulas']);
        return view('livewire.aula.lista-aulas');
    }
}
