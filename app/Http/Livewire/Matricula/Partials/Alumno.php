<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\EJB\CountryEJB;
use App\EJB\DistrictEJB;
use App\EJB\EntityEJB;
use App\EJB\SchoolEJB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Alumno extends Component
{
    public $id_alumno;
    public $formularioAlumno;

    public $lista_distritos, $lista_ie_procedencia;
    private $distritosEJB, $ie_procedenciaEJB,  $entityEJB;

    protected $listeners = [
        'ya-cargue' => 'getDatosAutocomplete'
    ];

    protected $rules = [
        'formularioAlumno.dni' => "required | integer | min:8",
        'formularioAlumno.f_nac' => "required | date_format:Y-m-d",
        'formularioAlumno.telefono' => "required | string | min: 4",
        'formularioAlumno.distrito' => "required | string ",
        'formularioAlumno.direccion' => "required | string | min: 4",
        'formularioAlumno.nombres' => "required | string | min: 4",
        'formularioAlumno.ap_paterno' => "required | string | min: 4",
        'formularioAlumno.ap_materno' => "required | string | min: 4",
        'formularioAlumno.Ie_procedencia' => "required | string | min: 4",
        'formularioAlumno.anio_egreso' => "required | date_format:Y",
        'formularioAlumno.sexo' => "required | string | min:4 | max:8",
    ];

    public function __construct()
    {
        $this->distritosEJB = new DistrictEJB();
        $this->ie_procedenciaEJB = new SchoolEJB();
        $this->entityEJB = new EntityEJB();
    }


    public function getDatosAutocomplete()
    {
        $this->emit('data-autocomplete', (object)[
            'lista_distritos' => $this->lista_distritos,
            'lista_ie_procedencia' => $this->lista_ie_procedencia,
            //'lista_paises' => $this->lista_paises
        ]);
    }

    public function initialState()
    {
        $this->reset(["formularioAlumno"]);

        $this->lista_distritos = $this->distritosEJB->listaDistritos();
        $this->lista_ie_procedencia = $this->ie_procedenciaEJB->listarEscuelas();
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
        $this->validate();
        $this->emit('alert-danger', (object) ['titulo' => 'prueba', 'mensaje' => ((object)$this->formularioAlumno)->dni]);

        Log::debug((array)$this->formularioAlumno);

        $entidad = $this->entityEJB->registrarEntidad((object) $this->formularioAlumno);
        Log::debug($entidad);
    }

    public function updated($nombre_var)
    {
        $this->validateOnly($nombre_var);
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
