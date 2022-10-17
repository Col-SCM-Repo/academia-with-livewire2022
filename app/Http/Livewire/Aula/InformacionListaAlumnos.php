<?php

namespace App\Http\Livewire\Aula;

use App\Repository\ClassroomRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\ExamRepository;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class InformacionListaAlumnos extends Component
{
    public $aula_id;
    public $search, $listaAlumnos;
    public $listaExamenes, $examenSeleccionadoId;

    private $_examenesRepository;

    // Formulario de evaluaciones
    public $matriculaId, $examenesMarcados;
    private $_aulaRepository, $_matriculaRepository;

    protected $rules = [
        'matriculaId' => 'required|int|min:1',
        'examenesMarcados' => 'required|array',
        'examenesMarcados.*.exam_resumen_id' => 'required|int|min:1',
        'examenesMarcados.*.nombre' => 'required|string',
        'examenesMarcados.*.fecha' => 'required|string',
        'examenesMarcados.*.check' => 'required|bool',

    ];

    public function initialState () {
        $this->reset([ 'search', 'listaAlumnos'  ]);
        $this->reset([ 'matriculaId', 'examenesMarcados' ]);
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

    public function openBtnModalEvaluacion( int $matricula_id ){
        $this->matriculaId = $matricula_id;

        $matricula = $this->_matriculaRepository::find($matricula_id);
        $examenesMarcados = array();
        foreach ( $matricula->examSummaries as $resumenExamen)
            $examenesMarcados[] = [
                "exam_resumen_id" => $resumenExamen->id,
                "nombre" => $resumenExamen->exam->name,
                "fecha" => date('Y/m/d h:i:s a', strtotime($resumenExamen->exam->exam_date)),
                "check" => false,
            ];
        $this->examenesMarcados = $examenesMarcados;
        openModal($this, '#form-modal-evaluacion-individual');

    }

    public function onBtnGenerarReporteIndividual(){
        $this->validate();
        $examenesMarcados = json_encode(array_map(fn($exmn)=>$exmn['exam_resumen_id'], array_filter($this->examenesMarcados, fn($exam)=>$exam['check'])));
        $this->emit('abrir-url-blank', route('reporte.evaluacion.detallado', [ 'json_examen_resumen_ids'=>$examenesMarcados  ])  );
        openModal($this, '#form-modal-evaluacion-individual', false);
    }

    public function openBtnModalEvaluacionMasivo(  ){
        if(Session::has('periodo'))
            $this->listaExamenes =$this->_examenesRepository->listaExamenesPorPeriodo( Session::get('periodo')->id );
        else
            $this->listaExamenes = [];
        openModal($this, '#form-modal-evaluacion-masiva');
    }

    public function generarReporteMasivo(){
        $this->validate([ 'examenSeleccionadoId' => 'required | integer | min:1' ]);
        $this->emit('abrir-url-blank', route('reporte-aula.evaluacion.detallado', [ 'examen_id'=>$this->examenSeleccionadoId, 'aula_id'=> $this->aula_id ])  );
        /* dd('Generando ...', $this->examenSeleccionadoId); */
        $this->reset(['examenSeleccionadoId']);
        openModal($this, '#form-modal-evaluacion-masiva', false);
    }

}
