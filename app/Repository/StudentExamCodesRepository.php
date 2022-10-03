<?php

namespace App\Repository;

use App\Models\StudentExamCodes;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class StudentExamCodesRepository extends StudentExamCodes
{
    private $_matriculaRepository;
    public function __construct()
    {
        $this->_matriculaRepository = new EnrollmentRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            'id' => null,
        ];
    }

    public function generarCodigosExamen( ){
        if( ! Session::has('periodo') ) throw new NotFoundResourceException('Error, no se encontro periodo activo');
        $periocoId = Session::get('periodo')->id;
        $matriculas = $this->_matriculaRepository->matriculasActivasCiclo($periocoId);
        $codesGenerados = array();

        foreach ($matriculas as $matricula) {
            $apellidos = $matricula->student->entity->father_lastname.' '.$matricula->student->entity->mother_lastname;
            $nombres = $matricula->student->entity->name;
            $type_code_id = $matricula->type_id;
            $codesGenerados [] = self::createEstudianteCodigoExamen($apellidos, $nombres,$matricula->code, $type_code_id, $matricula );
        }

        return $codesGenerados;
    }

    public function resetearCodigosExamen(){
        $codigosRegistrados = self::all();
        foreach ( $codigosRegistrados  as $codigoExamen) {
            $codigoExamen->delete();
        }
        return count($codigosRegistrados);
    }

    public function estudiantesRegistrados ( $type_code_id ){
        $estudiantesRegistrados = array();
        foreach ( self::where('type_code_id', $type_code_id, 'asc' )->get() as $index => $estudianteCode) {
            if($estudianteCode->enrollment){
                $estudianteCode->level = $estudianteCode->type_code->description;
                $estudianteCode->classroom = $estudianteCode->enrollment->classroom->name;
            }
            $estudiantesRegistrados [$index] = $estudianteCode;
        }
        return $estudiantesRegistrados;
    }

    public function createEstudianteCodigoExamen( string $apellidos, string $nombres, string $codigo_estudiante, int $type_code_id=null , $matricula=null){
        $estudianteCodigoExamen = new StudentExamCodes();
        $estudianteCodigoExamen->type_code_id = $type_code_id;
        $estudianteCodigoExamen->code_exam = null;
        $estudianteCodigoExamen->surname = $apellidos;
        $estudianteCodigoExamen->name = $nombres;
        $estudianteCodigoExamen->observation = null;

        $longitudCodigo = strlen($codigo_estudiante);
        $estudianteCodigoExamen->enrollment_id = $matricula? $matricula->id : null;
        $estudianteCodigoExamen->enrollment_code = substr($codigo_estudiante, $longitudCodigo-4,4);

        $estudianteCodigoExamen->save();
        return $estudianteCodigoExamen;
    }

}
