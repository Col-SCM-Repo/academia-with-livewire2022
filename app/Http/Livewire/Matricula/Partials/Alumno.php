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

    public $dni, $f_nac, $telefono, $distrito, $direccion, $nombres, $ap_paterno, $Ie_procedencia, $anio_egreso, $ap_materno, $sexo, $pais;

    public $lista_distritos, $lista_ie_procedencia;
    //, $lista_paises
    private $distritosEJB, $ie_procedenciaEJB,  $entityEJB;
    //$paisesEJB,
    public function __construct()
    {
        $this->distritosEJB = new DistrictEJB();
        $this->ie_procedenciaEJB = new SchoolEJB();
        //$this->paisesEJB = new CountryEJB();
        $this->entityEJB = new EntityEJB();
    }

    protected $listeners = [
        'ya-cargue' => 'getDatosAutocomplete'
    ];

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
        $this->sexo = "";
        $this->pais = "";

        $this->lista_distritos = $this->distritosEJB->listaDistritos();
        $this->lista_ie_procedencia = $this->ie_procedenciaEJB->listarEscuelas();
        // $this->lista_paises = $this->paisesEJB->listaPaises();
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
        Log::debug([
            "dni" => $this->dni,
            "f_nac" => $this->f_nac,
            "telefono" => $this->telefono,
            "distrito" => $this->distrito,
            "direccion" => $this->direccion,
            "nombres" => $this->nombres,
            "ap_paterno" => $this->ap_paterno,
            "Ie_procedencia" => $this->Ie_procedencia,
            "anio_egreso" => $this->anio_egreso,
            "ap_materno" => $this->ap_materno,
        ]);

        $this->validate([
            'dni' => "required | integer ",
            'f_nac' => "required | date_format:Y-m-d",
            'telefono' => "required | string | min: 4",
            'distrito' => "required | string ",
            'direccion' => "required | string | min: 4",
            'nombres' => "required | string | min: 4",
            'ap_paterno' => "required | string | min: 4",
            'ap_materno' => "required | string | min: 4",
            'Ie_procedencia' => "required | string | min: 4",
            'anio_egreso' => "required | date_format:Y",
            'sexo' => "required | string | min:4 | max:5",
        ]);
        Log::debug("Hola mindo");
        $this->emit('alert-danger', (object) ['titulo' => 'prueba', 'mensaje' => $this->nombres]);

        return 0;
        /* 
        'dni' => '123123123',
        'f_nac' => '2022-07-09',
        'telefono' => 'asdasdasd',
        'distrito' => 'SAN ISIDRO DE MAINO',
        'direccion' => 'asdasdasd',
        'nombres' => 'asdasdasda',
        'ap_paterno' => 'sdasdasda',
        'Ie_procedencia' => 'Colegio Cabrera',
        'anio_egreso' => '2112',
        'ap_materno' => 'sdasdasd',
 */

        $this->entityEJB->registrarEntidad($this);
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
