<?php

namespace App\Http\Livewire\Matricula\Partials;

use Livewire\Component;

class Apoderado extends Component
{
    public $idApoderado;
    public $formularioApoderado;

    /* public $lista_distritos, $lista_ocupacion;
    private $distritosRepository, $ocupacionRepository,  $entityRepository, $estudianteRepository; */

    protected $listeners = [
        'ya-cargue' => 'getDatosAutocomplete'
    ];

    protected $rules = [
        'formularioApoderado.dni' => "required | integer | min:8",
        'formularioApoderado.f_nac' => "required | date_format:Y-m-d",
        'formularioApoderado.telefono' => "required | string | min: 4",
        'formularioApoderado.distrito' => "required | string ",
        'formularioApoderado.direccion' => "required | string | min: 4",
        'formularioApoderado.nombres' => "required | string | min: 4",
        'formularioApoderado.ap_paterno' => "required | string | min: 4",
        'formularioApoderado.ap_materno' => "required | string | min: 4",
        'formularioApoderado.ocupacion' => "required | string | min: 4",
        'formularioApoderado.anio_egreso' => "required | date_format:Y",
        'formularioApoderado.sexo' => "required | string | min:4 | max:8",
        'formularioApoderado.parentesco' => "required | string | min:4",

    ];

    public function render()
    {
        return view('livewire.matricula.partials.apoderado');
    }


    public function getDatosAutocomplete()
    {
        /* $this->emit('data-autocomplete', (object)[
            'lista_distritos' => $this->lista_distritos,
            'lista_ocupacion' => $this->lista_ocupacion,
        ]); */
    }

    public function initialState()
    {
        $this->reset(["formularioApoderado"]);
    }

    public function mount()
    {
        self::initialState();
    }

    public function create()
    {
        /* $this->validate();
        Log::debug((array)$this->formularioApoderado);
        $entidad = $this->entityRepository->registrarEntidad((object) $this->formularioApoderado);
        $estudiante = $this->estudianteRepository->registrarEstudiante((object) $this->formularioApoderado, $entidad);

        if ($estudiante)
            $this->emit('alert-sucess', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno registrado correctamente. ']);
        else
            $this->emit('alert-warning', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno no fue encontradp. ']);

        $this->reset(["formularioApoderado", "idApoderado "]); */
    }

    public function update()
    {
        $this->validate();
        /* 
        $seActualizo = $this->estudianteRepository->actualizarEstudiante((object) $this->formularioApoderado, $this->idApoderado );
        if ($seActualizo)
            $this->emit('alert-sucess', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno se actualizo correctamente. ']);
        else
            $this->emit('alert-warning', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno no fue encontradp. ']); */
    }

    public function buscar_interno()
    {
        /*  $informacionAlumno = $this->estudianteRepository->getInformacionEstudiante($this->formularioApoderado['dni']);

        if ($informacionAlumno) {
            $this->formularioApoderado = [
                'dni' => $informacionAlumno->dni,
                'f_nac' => $informacionAlumno->fechaNacimiento,
                'telefono' => $informacionAlumno->telefono,
                'distrito' => $informacionAlumno->distrito,
                'direccion' => $informacionAlumno->direccion,
                'nombres' => $informacionAlumno->nombre,
                'ap_paterno' => $informacionAlumno->apPaterno,
                'ap_materno' => $informacionAlumno->apMaterno,
                'ocupacion' => $informacionAlumno->ieProcedencia,
                'anio_egreso' => $informacionAlumno->anioGraduacion,
                'sexo' => $informacionAlumno->sexo,
            ];
            $this->idApoderado  = $informacionAlumno->idApoderado ;
        } else {
            $this->emit('alert-warning', (object) ['titulo' => 'Alerta', 'mensaje' => 'El alumno no fue encontradp. ']);
        } */
    }
}
