<?php

namespace App\Http\Livewire\Matricula\Apoderado;

use App\Repository\RelativeRepository;
use Livewire\Component;

class ApoderadosTEMP
{
    public $search = "";
    public $apoderados = [];

    private $apoderadoRepository;

    public function __construct()
    {
        $this->apoderadoRepository = new RelativeRepository();
    }

    /*  
    public function render()
    {
        return view('livewire.matricula.apoderados-matricula');
    } 
    */
}
