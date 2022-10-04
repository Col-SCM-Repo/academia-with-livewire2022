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
            $codesGenerados [] = self::createEstudianteCodigoExamen($apellidos, $nombres,$matricula->code, $matricula );
        }

        return count($codesGenerados);
    }

    public function resetearCodigosExamen(){
        $codigosRegistrados = self::all();
        foreach ( $codigosRegistrados  as $codigoExamen) {
            $codigoExamen->delete();
        }
        return count($codigosRegistrados);
    }

    public function estudiantesRegistrados ( $level_name, $busqueda = '' ){
        $estudiantesRegistrados = array();
        $estudiantes = self::where('level', $level_name)
                            ->where( function($query) use ($busqueda){
                                $query  ->orWhere('classroom', 'like', "%$busqueda%")
                                        ->orWhere('surname', 'like', "%$busqueda%")
                                        ->orWhere('name', 'like', "%$busqueda%");
                            })
                            ->orderBy('classroom')
                            ->orderBy('surname')
                            ->orderBy('name')
                            ->get();

        foreach ( $estudiantes as $index => $estudianteCode)
            $estudiantesRegistrados [$index] = $estudianteCode->toArray();
        return $estudiantesRegistrados;
    }

    public function createEstudianteCodigoExamen( string $apellidos, string $nombres, string $codigo_estudiante, $matricula=null){
        $estudianteCodigoExamen = new StudentExamCodes();

        $longitudCodigo = strlen($codigo_estudiante);
        $estudianteCodigoExamen->enrollment_code = substr($codigo_estudiante, $longitudCodigo-4,4);
        /* $estudianteCodigoExamen->exam_code = null; */
        $estudianteCodigoExamen->observation = null;
        $estudianteCodigoExamen->surname = $apellidos;
        $estudianteCodigoExamen->name = $nombres;

        if($matricula){
            $estudianteCodigoExamen->enrollment_id = $matricula->id;
            $estudianteCodigoExamen->level = $matricula->classroom->level->level_type->description;
            $estudianteCodigoExamen->classroom = $matricula->classroom->name;
        }else{
            $estudianteCodigoExamen->level = 'LIBRE';
            $estudianteCodigoExamen->classroom = 'LIBRE';
        }
        $estudianteCodigoExamen->save();
        return $estudianteCodigoExamen;
    }

    public function actualizarEstudianteCodeExm ( $id, string $nombre, string $apellidos, string $codigo ){
        $estudianteCode = self::find($id);
        if(!$estudianteCode) throw new NotFoundResourceException('Error, no se encuentra el codigo del estudiante. ');

        $estudianteCode->exam_code = $codigo ;
        $estudianteCode->surname = $apellidos ;
        $estudianteCode->name = $nombre ;
        $estudianteCode->save();

        return $estudianteCode;
    }

}
