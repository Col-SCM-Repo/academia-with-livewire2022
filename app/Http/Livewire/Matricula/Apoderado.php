<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosEntidadEnum;
use App\Repository\DistrictRepository;
use App\Repository\OccupationRepository;
use App\Repository\RelativeRepository;
use Livewire\Component;

class Apoderado extends Component
{
    public $idRelacionApoderado;
    public $formularioApoderado;

    public $lista_distritos, $lista_ocupaciones;
    private $distritosRepository, $ocupacionRepository,  $entityRepository, $apoderadoRepository;
    private $componenteExterno;

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

    public function __construct()
    {
        $this->apoderadoRepository = new RelativeRepository();
        $this->distritosRepository = new DistrictRepository();
        $this->ocupacionRepository = new OccupationRepository();
    }

    public function mount($ambito = 0) // 1 = externo  <> 0 = componente interno
    {
        self::initialState();
        $this->componenteExterno = $ambito == 1;
    }

    public function render()
    {
        return view('livewire.matricula.apoderado');
    }

    public function initialState()
    {
        $this->reset(['formularioApoderado', 'idRelacionApoderado']);
    }

    public function create()
    {
        $this->validate();
        $apoderadoCreado = $this->apoderadoRepository->registrarApoderado(convertArrayUpperCase($this->formularioApoderado));
        if ($apoderadoCreado) {
            sweetAlert($this, 'apoderado', EstadosEntidadEnum::CREATED);
            $this->emit('apòderado_id', $apoderadoCreado->id);
        } else
            toastAlert($this, 'Primero verifique haber realizado la busqueda.');
    }

    public function update()
    {
        $this->validate();
        $data = convertArrayUpperCase($this->formularioApoderado);
        if ($this->apoderadoRepository->actualizarApoderado($this->idRelacionApoderado, $data)) {
            sweetAlert($this, 'apoderado', EstadosEntidadEnum::UPDATED);
        } else
            toastAlert($this, 'No se encontrò al apoderado.');
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
            $this->emit('apòderado_id', $informacionApoderado->idRelacionApoderado);
            $this->validate();
        } else {
            toastAlert($this, 'No se encontrò al apoderado.');
            $this->initialState();
        }
    }
}
