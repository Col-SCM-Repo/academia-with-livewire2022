<?php

namespace App\Http\Livewire\Evaluacion\Partials;

use App\Repository\ExamRepository;
use App\Repository\GroupRepository;
use App\Repository\LevelRepository;
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfiguracionBasica extends Component
{
    public  $examen_id;
    public  $nombre, $tipo_examen, $grupo_id, $codigo_de_grupo, $nivel_id,
            $numero_preguntas, $fecha_examen, $valor_preguntas_incorrectas;

    public  $lista_grupos, $lista_niveles;
    private $_grupoRepository, $_nivelRepository, $_examenRepository;

    public function __construct() {
        $this->_grupoRepository = new GroupRepository();
        $this->_nivelRepository = new LevelRepository();
        $this->_examenRepository = new ExamRepository();
    }

    protected $listeners = [
        'renderizar' => 'renderizar',
        'resetear-configuracion-basica' => 'initialState',
    ];
    /*
            'resetear-configuracion-basica'
    */

    protected $rules = [
        'nombre' => 'required|string',
        'tipo_examen' => 'required|in:simulacrum,monthly,weekly,daily,quick,other',
        'grupo_id' => 'required|integer|min:1',
        'codigo_de_grupo' => 'required|integer|min:0|max:99',
        'nivel_id' => 'nullable|integer|min:1',
        'numero_preguntas' => 'required',
        'fecha_examen' => 'required|date',
        'valor_preguntas_incorrectas' => 'required|numeric|min:0',
    ];

    public function initialState(){
        $this->reset(['examen_id']);
        $this->reset(['nombre','tipo_examen','grupo_id','codigo_de_grupo','nivel_id','numero_preguntas','fecha_examen','valor_preguntas_incorrectas']);
    }

    public function mount(){
        self::initialState();
        $this->lista_grupos = $this->_grupoRepository::all();
        $this->lista_niveles = $this->_nivelRepository::all();
    }

    public function render() {
        toastAlert($this, 'Renderizando partials.configuracion-basica', 'warning');
        return view('livewire.evaluacion.partials.configuracion-basica');
    }

    // Funciones para CRUD ( CU )
    public function create(){
        $this->validate();
        try {
            $moExamen = self::buildModelExamen();
            $examen = $this->_examenRepository->registrar( $moExamen );
            $this->examen_id = $examen->id;
            $this->emitUp('renderizar-componentes', $this->examen_id);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function update(){
        $this->validate();
        $this->validate([ 'examen_id' => 'required | integer | min:1' ]);

        try {
            $moExamen = self::buildModelExamen();
            $this->_examenRepository->actualizar( $this->examen_id, $moExamen );
            $this->emitUp('renderizar-componentes', $this->examen_id);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    // Funciones de listener
    /* public function resetFormulario(){

        $this->reset(['examen_id']);
        $this->reset(['nombre','tipo_examen','grupo_id','codigo_de_grupo','nivel_id','numero_preguntas','fecha_examen','valor_preguntas_incorrectas']);
    } */
    public function renderizar( $examen_id ){
        self::initialState();

        $examen = $this->_examenRepository::find($examen_id);
        if(!$examen) return;
        $this->examen_id = $examen->id;
        $this->nombre = $examen->name;
        $this->tipo_examen = $examen->evaluation_type;
        $this->grupo_id = $examen->group_id;
        $this->codigo_de_grupo = $examen->group_code;
        $this->nivel_id = $examen->level_id;
        $this->numero_preguntas = $examen->number_questions;
        $this->fecha_examen = $examen->exam_date;
        $this->valor_preguntas_incorrectas = $examen->score_wrong;
    }

    // Funciones internas (privadas)
    private function buildModelExamen(){
        if( !Session::has('periodo') ) throw new NotFoundHttpException('Error, no se encontro el periodo');

        $modeloExamen = $this->_examenRepository->builderModelRepository();
        $modeloExamen->periodo_id = Session::get('periodo')->id ;
        $modeloExamen->nivel_id = $this->nivel_id ;
        $modeloExamen->grupo_id = $this->grupo_id ;
        $modeloExamen->codigo_grupo = $this->codigo_de_grupo ;
        $modeloExamen->nombre = $this->nombre ;
        $modeloExamen->numero_preguntas = $this->numero_preguntas ;
        $modeloExamen->puntaje_incorrectas = $this->valor_preguntas_incorrectas ;
        $modeloExamen->tipo_evaluacion = $this->tipo_examen ;
        $modeloExamen->fecha_examen = $this->fecha_examen ;
        /* $modeloExamen->ruta_archivo = $this->qqqqq ; */
        return $modeloExamen;
    }

}
