<?php

namespace App\Http\Livewire\Matricula\Alumno;

use App\Repository\StudentRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AlumnoTEMP
{
    public $search = "";
    public $alumnos = [];

    private $alumnosRepository;

    public function __construct()
    {
        $this->alumnosRepository = new StudentRepository();
    }
    /* 
    public function render()
    {
        $this->alumnos = $this->alumnosRepository->getListaAlumnos($this->search);
        Log::debug($this->alumnos);
        return view('livewire.matricula.alumnos-matricula');
    } 
    */
}