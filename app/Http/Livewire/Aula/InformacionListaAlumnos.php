<?php

namespace App\Http\Livewire\Aula;

use App\Repository\ClassroomRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\ExamRepository;
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class InformacionListaAlumnos extends Component
{
    public  $aula_id;
    public  $search, $listaAlumnos;

    // Formulario generacion masiva
    public  $listaExamenes,
            $examenSeleccionadoId, $mostrarTodosLosAlumnos,
            $fechaInicio, $fechaFinal,
            $tipoGeneracion;

    // Formulario generacion individual
    public  $matriculaId, $examenesMarcados;

    private $_aulaRepository, $_matriculaRepository, $_examenesRepository;

    /* protected $rules = [
    ]; */

    public function initialState () {
        $this->reset(['search', 'listaAlumnos'  ]);
        $this->reset(['listaExamenes','examenSeleccionadoId', 'mostrarTodosLosAlumnos', 'fechaInicio','fechaFinal','tipoGeneracion' ]);
        $this->reset(['matriculaId', 'examenesMarcados' ]);
    }

    public function __construct()
    {
        $this->_aulaRepository = new ClassroomRepository();
        $this->_matriculaRepository = new EnrollmentRepository();
        $this->_examenesRepository = new ExamRepository();
    }

    public function mount( $aula_id = null ){
        $this->aula_id = $aula_id;
    }

    public function render()
    {
        $this->listaAlumnos = $this->_aulaRepository->listaEstudiantes($this->aula_id, $this->search);
        return view('livewire.aula.informacion-lista-alumnos');
    }

    public function updatingTipoGeneracion( $valor ){
        $this->reset(['matriculaId','examenesMarcados']);
        switch($valor){
            case 'especifico':
                    $this->reset(['fechaInicio','fechaFinal']);
                    $this->mostrarTodosLosAlumnos = false;
                break;
                case 'fechas':
                    $this->reset(['examenSeleccionadoId', 'mostrarTodosLosAlumnos']);
                break;
            default:
                $this->reset(['examenSeleccionadoId', 'mostrarTodosLosAlumnos', 'fechaInicio', 'fechaFinal' ]);
            break;
        }
    }

    public function openBtnModalEvaluacionIndividual( int $matricula_id ){
        $this->reset(['listaExamenes', 'examenSeleccionadoId', 'mostrarTodosLosAlumnos','fechaInicio', 'fechaFinal' ]);
        $this->matriculaId = $matricula_id;

        $examenesMarcados = array();
        foreach ($this->_matriculaRepository::find($matricula_id)->examSummaries as $resumenExamen)
            $examenesMarcados[] = [
                "exam_resumen_id" => $resumenExamen->id,
                "exam_id" => $resumenExamen->exam_id,
                "nombre" => $resumenExamen->exam->name,
                "fecha" => date('Y/m/d h:i:s a', strtotime($resumenExamen->exam->exam_date)),
                "check" => false,
            ];
        $this->examenesMarcados = $examenesMarcados;
        openModal($this, '#form-modal-evaluacion-individual');
    }

    public function openBtnModalEvaluacionMasivo(  ){
        $this->reset(['tipoGeneracion' ]);
        if(Session::has('periodo'))
            $this->listaExamenes =$this->_examenesRepository->listaExamenesPorPeriodo( Session::get('periodo')->id );
        else
            $this->listaExamenes = [];
        openModal($this, '#form-modal-evaluacion-masiva');
    }

    public function onBtnGenerarReporteIndividual(){
        $this->validate([
            'matriculaId' => 'required|int|min:1',
            'examenesMarcados' => 'required|array|min:1',
            'examenesMarcados.*.exam_resumen_id' => 'required|int|min:1',
            'examenesMarcados.*.nombre' => 'required|string',
            'examenesMarcados.*.fecha' => 'required|string',
            'examenesMarcados.*.check' => 'required|bool'
        ]);

        try {
            $rutaArchivo = self::builJsonEncodeURL('especifico');
            $this->emit('abrir-url-blank', $rutaArchivo );

            openModal($this, '#form-modal-evaluacion-individual', false);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function onBtngenerarReporteMasivo(){
        $this->validate([ 'tipoGeneracion' => 'required|string|in:especifico,fechas' ]);
        if ($this->tipoGeneracion == 'especifico')
            $this->validate([   'examenSeleccionadoId' => 'required|integer|min:1',
                                'mostrarTodosLosAlumnos' => 'bool' ]);
        else
            $this->validate([   'fechaInicio' => 'required|date',
                                'fechaFinal' => 'required|date' ]);
        try {
            $rutaArchivo = self::builJsonEncodeURL('masivo');
            $this->emit('abrir-url-blank', $rutaArchivo );

            openModal($this, '#form-modal-evaluacion-masiva', false);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    private function builJsonEncodeURL( $tipoGeneracion){
        $data = null;
        switch ($tipoGeneracion) {
            case 'masivo':
                switch ( $this->tipoGeneracion ) {
                    case 'especifico':      // indica examen id
                        $data = [
                            'generacion' => 'masivo',
                            'tipo' => 'especifico',
                            'examen_IDs' => [ $this->examenSeleccionadoId ] ,
                            'aula_ID' => $this->aula_id ,
                            'todosLosAlumnos' => $this->mostrarTodosLosAlumnos ,
                        ];
                        break;
                    case 'fechas':
                            $examenIDsRangos = array_map(   fn($examen)=> $examen['id'] ,
                                                            array_filter( $this->listaExamenes,
                                                            fn($exmn)=> (   strtotime($this->fechaInicio.' 00:00:00') < strtotime($exmn['exam_date']) &&
                                                                            strtotime($exmn['exam_date']) < strtotime($this->fechaFinal.' 23:59:59'))) );
                            if( ! count($examenIDsRangos)>0 ) throw new Exception('No se encontró examenes en el rango de fecha indicado');

                            $data = [   'generacion' => 'masivo',
                                        'tipo' => 'fechas',
                                        'examen_IDs' => $examenIDsRangos,
                                        'aula_ID' => $this->aula_id,
                                        'todosLosAlumnos' => true ,
                                    ];
                        break;
                }
                break;
            case 'especifico':
                $examenesMarcadosIDs = array_map(fn($exmn)=>$exmn['exam_id'], array_filter($this->examenesMarcados, fn($exam)=>$exam['check']));
                if( ! count($examenesMarcadosIDs)>0 ) throw new Exception('Debe seleccionar almenos un examen');

                $data = [   'generacion' => 'especifico',
                            'tipo' => 'especifico',
                            'matricula_ID' => $this->matriculaId,
                            'examen_IDs' => array_values($examenesMarcadosIDs),
                            'aula_ID' => $this->aula_id,
                        ];
                break;
        }
        return route('reporte.evaluacion.detallado', ['data_json'=>urlencode(json_encode($data))]);
    }

}
