<?php

namespace App\Http\Livewire\Evaluacion;

use App\Repository\ExamRepository;
use Exception;
use Livewire\Component;

class RevisionExamen extends Component
{
    public $fechaInicio, $fechaFin;
    public $listaExamenesDisponibles;

    private $_examenesRepository;

    public function __construct()
    {
        $this->_examenesRepository = new ExamRepository();
    }

    public function render()
    {
        toastAlert($this, 'Renderizando evaluaciones.examen', 'warning');
        return view('livewire.evaluacion.revision-examen');
    }

    public function onClickBtnSearchExams(){
        try {
            $this->listaExamenesDisponibles = $this->_examenesRepository->listaExamenesPorRango($this->fechaInicio, $this->fechaFin);
        } catch (Exception $err ) {
            toastAlert($this, $err->getMessage() );
            $this->reset(['listaExamenesDisponibles']);
        }

    }

}
