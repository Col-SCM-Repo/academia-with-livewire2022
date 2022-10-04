<?php

namespace App\Http\Livewire\Evaluacion;

use App\Repository\ExamRepository;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;

class RevisionExamen extends Component
{
    use WithFileUploads;

    public $fechaInicio, $fechaFin;
    public $archivoCartilla;

    public $listaExamenesDisponibles;

    private $_examenesRepository;


    protected $rules = [
        'archivoCartilla' => 'required|file|max:1024',
    ];


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
