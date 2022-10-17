<?php

namespace App\Http\Livewire\Aula;

use App\Repository\ExamRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class InformacionGeneral extends Component
{
    public $aulaId;
    public $listaExamenes, $examenSeleccionadoId;

    public function mount( $aula_id ){
        $this->aulaId = $aula_id;
    }

    public function __construct()
    {
    }

    public function render()
    {
        return view('livewire.aula.informacion-general');
    }


}
