<?php

namespace App\Http\Livewire\Matricula\Partials;

use Livewire\Component;

class Alumno extends Component
{

    public $id_alumno;

    public $dni, $f_nac, $telefono, $distrito, $direccion, $nombres, $ap_paterno, $Ie_procedencia, $anio_egreso, $ap_materno, $sexo, $pais;

    public $lista_distritos, $lista_ie_procedencia, $lista_paises;


    public function initialState()
    {
        $this->id_alumno = null;
        $this->dni = "";
        $this->f_nac = "";
        $this->telefono = "";
        $this->distrito = "";
        $this->direccion = "";
        $this->nombres = "";
        $this->ap_paterno = "";
        $this->Ie_procedencia = "";
        $this->anio_egreso = "";
        $this->ap_materno = "";
    }

    public function mount()
    {
        self::initialState();
    }

    public function render()
    {
        return view('livewire.matricula.partials.alumno');
    }

    public function create()
    {
        $this->validate([
            $this->dni => "required | string | min: 4",
            $this->f_nac => "required | string | min: 4",
            $this->telefono => "required | string | min: 4",
            $this->distrito => "required | string ",
            $this->direccion => "required | string | min: 4",
            $this->nombres => "required | string | min: 4",
            $this->ap_paterno => "required | string | min: 4",
            $this->ap_materno => "required | string | min: 4",
            $this->Ie_procedencia => "required | string | min: 4",
            $this->anio_egreso => "required | string | min: 4",
        ]);
    }

    public function update()
    {
    }

    public function buscar_interno()
    {
        //$this->emit('alert-danger', (object) ['titulo' => 'prueba', 'mensaje' => 'Ejemplo de alerta']);

    }

    public function buscar_reniec()
    {
    }
}
