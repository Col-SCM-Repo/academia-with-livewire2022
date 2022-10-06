<?php

namespace App\Http\Livewire\Evaluacion;

use App\Enums\EstadosAlertasEnum;
use App\Repository\ExamQuestionRepository;
use App\Repository\ExamRepository;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class RevisionExamen extends Component
{
    use WithFileUploads;

    public $fechaInicio, $fechaFin;
    public $archivoCartilla;

    public $listaExamenesDisponibles;

    private $_examenesRepository, $_examenPreguntasRepository;


    protected $rules = [
        'listaExamenesDisponibles' => 'array|required',
        'listaExamenesDisponibles.*.archivo' => 'required|file|max:1024',
    ];


    public function __construct()
    {
        $this->_examenesRepository = new ExamRepository();
        $this->_examenPreguntasRepository = new ExamQuestionRepository();
    }

    public function render()
    {
        toastAlert($this, 'Renderizando evaluaciones.examen', 'warning');
        return view('livewire.evaluacion.revision-examen');
    }

    public function onClickBtnSearchExams(){
        try {
            $this->listaExamenesDisponibles = $this->_examenesRepository->listaExamenesPorRango($this->fechaInicio, $this->fechaFin);
        } catch (Exception $err ) {
            toastAlert($this, $err->getMessage() );
            $this->reset(['listaExamenesDisponibles']);
        }

    }

    public function onBtnCorregirExam( int $index ){

        $examen = $this->listaExamenesDisponibles[$index];
        if($examen['path'] && Storage::exists( $examen['path'] ) ){
            $inicioCaptura = 48;

            $longitudCodigoGrupo = 2;
            $longitudCodigoAlumno = 4;
            $numeroPreguntas = count($examen['questions']);

            if( !$numeroPreguntas>0 ) throw new Exception('No se encontro preguntas para el examen');

            $cartillasRespuestas = array();
            $fp = fopen(storage_path('app/'.$examen['path']), "r");
            while (!feof($fp)){
                $cadenaCartilla = substr( fgets($fp) , $inicioCaptura);

                $codigoGrupo  = substr($cadenaCartilla, 0, $longitudCodigoGrupo);
                $codigoAlumno = substr($cadenaCartilla, $longitudCodigoGrupo, $longitudCodigoAlumno);
                $respuestas   = str_split(substr($cadenaCartilla, $longitudCodigoGrupo + $longitudCodigoAlumno ));

                /* if( !$codigoGrupo==$examen['group_code'])
                throw new Exception( "Error, incongruencia de codigos de examen COD:$codigoGrupo , linea: ".(count($cartillasRespuestas)+1)  ); */

                $cartillasRespuestas [] = [ 'cod_grupo'=>$codigoGrupo, 'cod_alumno'=>$codigoAlumno, 'respuestas'=>$respuestas ];
            }
            fclose($fp);
            $statusCorreccion = $this->_examenPreguntasRepository->corregirExamen($examen['id'], $cartillasRespuestas);
        }
        else throw new Exception('Error, no se encontro la cartilla de respuestas');

    }


    public function updated($name, $value)
    {
        try {
            $nombre = explode('.', $name);
            if( count($nombre)==3 && $nombre[0]== 'listaExamenesDisponibles' && $nombre[2]  == 'archivo' ){
                $archivo = $this->listaExamenesDisponibles[ $nombre[1] ]['archivo'];
                $extension = pathinfo($archivo->getClientOriginalName(), PATHINFO_EXTENSION);
                if($archivo &&  ($extension == 'dat' || $extension == 'txt') ){
                    $ruta = 'examenes/'.Session::get('periodo')->year;
                    $nombreArchivo = urlencode (date('Y-m-d-H:i:s').'-'.$this->listaExamenesDisponibles[ $nombre[1] ]['name']).'.dat';
                    $archivo->storeAs($ruta, $nombreArchivo);
                    toastAlert($this, 'Archivo registrado correctamente', EstadosAlertasEnum::SUCCESS);
                    $this->_examenesRepository->agregarRutaExamen($this->listaExamenesDisponibles[ $nombre[1] ]['id'],$ruta.'/'.$nombreArchivo);
                    $this->listaExamenesDisponibles = $this->_examenesRepository->listaExamenesPorRango($this->fechaInicio, $this->fechaFin);
                }
                else throw new Exception('Error, el archivo cargado no es valido');
            }

        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }


}
