<?php

namespace App\Http\Livewire\Matricula\Partials;

use App\Repository\DistrictRepository;
use App\Repository\OccupationRepository;
use App\Repository\RelativeRepository;
use Livewire\Component;

class Apoderado extends Component
{
    public $idRelacionApoderado;
    public $formularioApoderado;

    public $lista_distritos, $lista_ocupaciones;
    protected $distritosRepository, $ocupacionRepository,  $entityRepository, $apoderadoRepository;

    public function __construct()
    {
        $this->apoderadoRepository = new RelativeRepository();
        $this->distritosRepository = new DistrictRepository();
        $this->ocupacionRepository = new OccupationRepository();
    }

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
        'formularioApoderado.sexo' => "required | string | min:4 | max:8",
        'formularioApoderado.estado_marital' => "required | string | min:4",
    ];

    public function render()
    {
        return view('livewire.matricula.partials.apoderado');
    }

    public function initialState()
    {
        $this->reset(['formularioApoderado', 'idRelacionApoderado']);
    }

    public function mount()
    {
        self::initialState();
    }

    public function create()
    {
        $this->validate();
        $data = convertArrayUpperCase($this->formularioApoderado);
        if ($this->apoderadoRepository->registrarApoderado($data))
            $this->emit('alert-sucess', (object) ['mensaje' => 'El apoderado se registró correctamente. ']);
        else
            $this->emit('alert-warning', (object) ['mensaje' => 'Verifique que halla realizado la busqueda en el sistema. ']);
        $this->initialState();
    }

    public function update()
    {
        $this->validate();
        $data = convertArrayUpperCase($this->formularioApoderado);
        if ($this->apoderadoRepository->actualizarApoderado($this->idRelacionApoderado, $data)) {
            $this->emit('alert-sucess', (object) ['mensaje' => 'El apoderado se registró correctamente.']);
            $this->initialState();
        } else
            $this->emit('alert-warning', (object) ['mensaje' => 'Verifique que el apodeado exista.']);
    }

    public function buscar_interno()
    {
        $this->validateOnly('formularioApoderado.dni');
        $informacionApoderado = $this->apoderadoRepository->getInformacionApoderado($this->formularioApoderado['dni']);

        if ($informacionApoderado) {
            $this->formularioApoderado = [
                'dni' => $informacionApoderado->dni,
                'f_nac' => $informacionApoderado->fechaNacimiento,
                'telefono' => $informacionApoderado->telefono,
                'distrito' => $informacionApoderado->distrito,
                'direccion' => $informacionApoderado->direccion,
                'nombres' => $informacionApoderado->nombre,
                'ap_paterno' => $informacionApoderado->apPaterno,
                'ap_materno' => $informacionApoderado->apMaterno,
                'ocupacion' => $informacionApoderado->ocupacion,
                'sexo' => $informacionApoderado->sexo,
                'estado_marital' => $informacionApoderado->estado_marital,
            ];
            $this->idRelacionApoderado  = $informacionApoderado->idRelacionApoderado;

            $this->emit('alert-success', (object) ['mensaje' => 'Si se encontro infornacion.']);
        } else {
            $this->emit('alert-warning', (object) ['mensaje' => 'El alumno no fue encontradp.']);
        }
    }
}
