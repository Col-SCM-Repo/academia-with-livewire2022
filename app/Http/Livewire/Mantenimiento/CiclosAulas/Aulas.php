<?php

namespace App\Http\Livewire\Mantenimiento\CiclosAulas;

use App\Enums\EstadosEntidadEnum;
use App\Repository\ClassroomRepository;
use App\Repository\LevelRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Aulas extends Component
{
    public $formularioAula, $aulaSeleccionada_id;

    public $listaNivelesActivos;
    private $_aulaRepository, $_nivelRepository;

    protected $listeners = [
        'periodo-actualizado' => 'render',
        'nivel-actualizado' => 'render',
        'eliminar-aula' => 'delete'
    ];

    protected $rules = [
        'formularioAula.nombre' => 'required | string | min:1 ',
        'formularioAula.nivel_id' => 'integer',
        'formularioAula.vacantes' => 'required | integer | min:1 ',
    ];
    // enrique garcia 

    public function __construct()
    {
        $this->_aulaRepository = new ClassroomRepository();
        $this->_nivelRepository = new LevelRepository();
    }

    public function initialState()
    {
        $this->reset(['aulaSeleccionada_id', 'formularioAula']);
    }
    public function render()
    {
        if (Session::has('periodo'))
            $this->listaNivelesActivos = $this->_nivelRepository->buscarAulasPorNivel(Session::get('periodo')->id);
        else
            $this->listaNivelesActivos = [];
        // Log::debug($this->listaNivelesActivos);
        return view('livewire.mantenimiento.ciclos-aulas.aulas');
    }

    // Modales
    public function openModalNuevaAula($nivel_id)
    {
        self::initialState();
        $this->formularioAula['nivel_id'] = $nivel_id;
        openModal($this, '#form-modal-aula');
    }

    public function openModalEditaAula(int $aula_id)
    {
        self::initialState();
        $aulaSeleccionadaTemp = $this->_aulaRepository::find($aula_id);
        if ($aulaSeleccionadaTemp) {
            $this->formularioAula = [
                'nombre' => $aulaSeleccionadaTemp->name,
                'vacantes' => $aulaSeleccionadaTemp->vacancy,
            ];
            $this->aulaSeleccionada_id = $aula_id;
            openModal($this, '#form-modal-aula');
        } else
            toastAlert($this, 'Error al encontrar el aula');
    }

    // Crud
    public function create()
    {
        $this->validate();
        if ($this->_aulaRepository->registrarClase(convertArrayUpperCase($this->formularioAula))) {
            sweetAlert($this, 'aula', EstadosEntidadEnum::CREATED);
            openModal($this, '#form-modal-aula', false);
            self::initialState();
        } else
            toastAlert($this, 'Error al registrar el aula');
    }


    public function update()
    {
        $this->validate(['aulaSeleccionada_id' => 'required | integer | min:0']);
        $this->validate();
        if ($this->_aulaRepository->actualizarClase($this->aulaSeleccionada_id, convertArrayUpperCase($this->formularioAula))) {
            openModal($this, '#form-modal-aula', false);
            sweetAlert($this, 'aula', EstadosEntidadEnum::UPDATED);
            self::initialState();
        } else
            toastAlert($this, 'Error al actualizar el aula');
    }

    public function delete($aula_id)
    {

        if ($this->_aulaRepository->eliminarClase($aula_id)) {
            sweetAlert($this, 'aula', EstadosEntidadEnum::DELETED);
            self::initialState();
        } else
            toastAlert($this, 'Error al eliminar periodo');
    }
}
