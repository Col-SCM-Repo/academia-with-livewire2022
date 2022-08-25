<?php

namespace App\Http\Livewire\Matricula;

use App\Enums\EstadosEntidadEnum;
use App\Enums\FormasPagoEnum;
use App\Enums\TiposParentescosApoderadoEnum;
use App\Repository\CareerRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EnrollmentRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Matricula extends Component
{
    public $matricula_id;
    public $formularioMatricula;

    public $lista_classrooms, $lista_carreras, $vacantes_total, $vacantes_disponible;
    private $_classroomRepository,  $_careersRepository, $_matriculaRepository;

    protected $rules = [
        'formularioMatricula.tipo_matricula' => 'required|string|in:normal,beca,semi-beca',
        'formularioMatricula.classroom_id' => 'required|integer|min:1',
        'formularioMatricula.carrera' => 'required|string | min:3',
        'formularioMatricula.tipo_pago' => 'required|string|in:cash,credit',    // agregar evento
        'formularioMatricula.cuotas' => 'required|integer|min:0|max:3',         // hacer dinamico
        'formularioMatricula.lista_Cuotas' => ' array',
        'formularioMatricula.lista_cuotas.*.costo' => 'numeric | min:0',
        'formularioMatricula.lista_cuotas.*.fecha' => 'date',
        'formularioMatricula.costo_matricula' => 'required | numeric | min:0',
        'formularioMatricula.costo' => 'required | numeric | min:0',
        'formularioMatricula.observaciones' => ' required | string ',

        //'formularioMatricula.relative_id' => 'required|numeric|min:1 ',
        'formularioMatricula.student_id' => 'required|numeric|min:1 ',
    ];

    protected $listeners = [
        'student-found' => 'setData',
        'change-prop-enrollment' => 'setData',

        'pagina-cargada-matricula' => 'enviarDataAutocomplete',
    ];


    public function __construct()
    {
        $this->_classroomRepository = new ClassroomRepository();
        $this->_careersRepository = new CareerRepository();
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function initialState()
    {
        $this->reset(['formularioMatricula', 'matricula_id']);
        $this->formularioMatricula['cuotas'] = 0;
        $this->formularioMatricula['lista_cuotas'] = array();
        //$this->formularioMatricula['relative_id'] = null;
        $this->formularioMatricula['student_id'] = null;
    }

    public function mount()
    {
        self::initialState();
        $this->lista_carreras =$this->_careersRepository->listarCarreras();

        //  temporal
        // $this->formularioMatricula['relative_id']=1;
        // $this->formularioMatricula['student_id']=1;
    }

    public function render()
    {
        $this->lista_classrooms = Session::has('periodo') ? $this->_classroomRepository->getListaClases(Session::get('periodo')->id) : [];
        return view('livewire.matricula.matricula');
    }

    public function updated($name, $value)
    {
        Log::debug($name);
        switch ($name) {
            case 'formularioMatricula.classroom_id':
                if ($value != null && $value != "") {
                    $this->vacantes_total = $this->lista_classrooms[$value]['total_vacantes'];
                    $this->vacantes_disponible = $this->lista_classrooms[$value]['vacantes_disponibles'];
                    $this->formularioMatricula['costo'] = $this->lista_classrooms[$value]['costo'];
                } else {
                    $this->vacantes_total = '';
                    $this->vacantes_disponible = '';
                    $this->formularioMatricula = '';
                }
                break;

            case 'formularioMatricula.cuotas':
                if ( is_numeric($value) && $value > 0)
                    $this->formularioMatricula['lista_cuotas'] = self::generarCuotasAutomatico();
                else
                    $this->formularioMatricula['lista_cuotas'] = null;
                break;

            case 'formularioMatricula.tipo_pago':
                switch ($value) {
                    case FormasPagoEnum::CONTADO:
                        $this->formularioMatricula['cuotas'] = 0;
                        $this->formularioMatricula['lista_cuotas'] = null;
                        $this->formularioMatricula['costo_matricula'] = 0;
                        break;
                    case FormasPagoEnum::CREDITO:
                        $this->formularioMatricula['cuotas'] = 2;
                        $this->formularioMatricula['lista_cuotas'] = self::generarCuotasAutomatico();
                        $this->formularioMatricula['costo_matricula'] = 50;
                        break;
                }
                break;

                case 'formularioMatricula.lista_cuotas.0.costo':
                case 'formularioMatricula.lista_cuotas.1.costo':
                    $this->validateOnly('formularioMatricula.lista_cuotas.*.costo');
                    try {
                        self::recalcularMontos();
                    } catch (Exception $err) {
                        toastAlert($this, $err->getMessage());
                    }
                    break;

            default:
                break;
        }
    }

    /***********************************************************  CRUD *************************************************************/
    public function create()
    {
        $this->validate();
        //dd($this->formularioMatricula);

        // $formularioMatriculaObj = convertArrayUpperCase($this->formularioMatricula);
        $modelMatricula = $this->_matriculaRepository->builderModelRepository();
        $modelMatricula->tipo_matricula = formatInputStr($this->formularioMatricula['tipo_matricula']);
        $modelMatricula->estudiante_id =$this->formularioMatricula['student_id'];
        $modelMatricula->aula_id = $this->formularioMatricula['classroom_id'];
        //$modelMatricula->apoderado_id = $this->relative_id;
        $modelMatricula->relacion_apoderado = TiposParentescosApoderadoEnum::PADRE; // Cambiar
        $modelMatricula->carrera =  formatInputStr($this->formularioMatricula['carrera']) ;
        $modelMatricula->tipo_pago = formatInputStr($this->formularioMatricula['tipo_pago']);
        $modelMatricula->cantidad_cuotas = formatInputStr($this->formularioMatricula['cuotas']);
        $modelMatricula->cuotas_detalle = $this->formularioMatricula['lista_cuotas'];
        $modelMatricula->costo_matricula = formatInputStr($this->formularioMatricula['costo_matricula']);
        $modelMatricula->costo_ciclo = $this->formularioMatricula['costo'];
        $modelMatricula->observaciones = $this->formularioMatricula['observaciones'];

        try {
            $matriculaCreada = $this->_matriculaRepository->registrar($modelMatricula);
            $this->matricula_id = $matriculaCreada->id;
            $this->emitTo('matricula.pago', 'enrollment-found', (object)[ 'name' => 'matricula_id', 'value'=> $matriculaCreada->id  ]);
            sweetAlert($this, 'matricula', EstadosEntidadEnum::CREATED);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function update()
    {
        $this->validate('formularioMatricula');

        sweetAlert($this, 'matricula', EstadosEntidadEnum::UPDATED);
    }



    /***********************************************************  Funciones listeners *************************************************************/
    public function enviarDataAutocomplete()
    {
        $this->emit('data-autocomplete-matricula', (object)[ "carreras" => $this->lista_carreras ]);
    }

    public function setData( $nuevaData )
    {
        // dd( $nuevaData,$this->formularioMatricula  );
        $this->formularioMatricula[$nuevaData['name']] = $nuevaData['value'];
    }

    /***********************************************************  Funciones internas *************************************************************/
    private function generarCuotasAutomatico ( bool $automatico = true ){
        if(!(isset($this->formularioMatricula['costo']) && is_numeric($this->formularioMatricula['costo']) && $this->formularioMatricula['costo']>0))
            return null;

        if(!( isset($this->formularioMatricula['cuotas']) && is_numeric($this->formularioMatricula['cuotas']) && $this->formularioMatricula['cuotas']>0 ))
            return null;

        if($automatico){
            $cuotasArray = array();

            $costo_ciclo = $this->formularioMatricula['costo'];
            $numero_cuotas = $this->formularioMatricula['cuotas'];
            $costo_cuota = round($costo_ciclo / $numero_cuotas, 2 );

            for ($i=0; $i < $numero_cuotas; $i++)
                $cuotasArray [$i] = [ 'costo' => $costo_cuota, 'fecha' => null] ;
            return $cuotasArray;
        }
        else{
            // Agregar nuevas politicas para el calculo de las cuotas de matricula
            return array();
        }
    }

    private function recalcularMontos( ){
        $costo_ciclo = $this->formularioMatricula['costo'];
        $numero_cuotas = count($this->formularioMatricula['lista_cuotas']) ;

        $monto_acumulador = 0;
        foreach ($this->formularioMatricula['lista_cuotas'] as $index => $cuota){
            if( $index == $numero_cuotas-1 ) break;
            $monto_acumulador += $cuota['costo'];
        }
        $this->formularioMatricula['lista_cuotas'][$numero_cuotas-1]['costo'] = $costo_ciclo - $monto_acumulador;
    }
}

