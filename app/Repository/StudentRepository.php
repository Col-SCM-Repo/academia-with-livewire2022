<?php

namespace App\Repository;

use App\Models\Student;
use Illuminate\Support\Facades\Log;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class StudentRepository extends Student
{
    private $entidadRepository, $escuelaRepository;

    /*      id	entity_id	school_id	graduation_year	photo_file */

    public function __construct()
    {
        $this->entidadRepository = new EntityRepository();
        $this->escuelaRepository = new SchoolRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            // Propiedades de la entidad
            "apellido_paterno" => null,         //  father_lastname
            "apellido_materno" => null,         //  mother_lastname
            "nombres" => null,                  //  name
            "direccion" => null,                //  address
            "distrito" => null,                 //  district_id
            "telefono" => null,                 //  telephone
            "celular" => null,                  //  mobile_phone
            "email" => null,                    //  email
            "fecha_nacimiento" => null,         //  birth_date
            "sexo" => null,                     //  gender
            "pais_id" => 173,                   //  country_id
            "tipo_documento" => "dni",          //  document_type
            "dni" => null,                      //  document_number
            "estado_marital" => null,           //  marital_status
            "grado_de_instruccion" => null,     //  instruction_degree

            // Propiedades de estudiante
            "nombre_escuela" => null,           //
            "anio_graduacion" => null,          //  graduation_year
            "nombre_archivo_foto" => null,      //  photo_file
        ];
    }

    public function registrar($moStudent)
    {
        $moEntidad = $this->entidadRepository->builderModelRepository();
        $moEntidad->apellido_paterno = $moStudent->apellido_paterno;
        $moEntidad->apellido_materno = $moStudent->apellido_materno;
        $moEntidad->nombres = $moStudent->nombres;
        $moEntidad->direccion = $moStudent->direccion;
        $moEntidad->distrito = $moStudent->distrito;
        $moEntidad->telefono = $moStudent->telefono;
        $moEntidad->celular = $moStudent->celular;
        $moEntidad->email = $moStudent->email;
        $moEntidad->fecha_nacimiento = $moStudent->fecha_nacimiento;
        $moEntidad->sexo = $moStudent->sexo;
        $moEntidad->dni = $moStudent->dni;
        $moEntidad->estado_marital = $moStudent->estado_marital;
        $moEntidad->grado_de_instruccion = $moStudent->grado_de_instruccion;
        $entidad = $this->entidadRepository->registrar($moEntidad);

        $estudiante = Student::where('entity_id', $entidad->id)->first();
        if ($estudiante) throw new BadRequestException('El alumno con dni '.$moEntidad->dni.' ya se encuentra registrado. ');

        $estudiante = new Student();
        $estudiante->entity_id = $entidad->id;
        $estudiante->school_id = $this->escuelaRepository->registrarBuscarEscuela($moStudent->nombre_escuela)->id;
        $estudiante->graduation_year = $moStudent->anio_graduacion;
        //$estudiante->photo_file = $moStudent->nombre_archivo_foto;
        $estudiante->save();
        return $estudiante;
    }

    public function actualizar( int $idEstudiante, object $moStudent)
    {
        $estudiante = Student::find($idEstudiante);
        if (!$estudiante) throw new NotFoundResourceException('Error, no se encontro al estudiante');

        $moEntidad = $this->entidadRepository->builderModelRepository();
        $moEntidad->apellido_paterno = $moStudent->apellido_paterno;
        $moEntidad->apellido_materno = $moStudent->apellido_materno;
        $moEntidad->nombres = $moStudent->nombres;
        $moEntidad->direccion = $moStudent->direccion;
        $moEntidad->distrito = $moStudent->distrito;
        $moEntidad->telefono = $moStudent->telefono;
        $moEntidad->celular = $moStudent->celular;
        $moEntidad->email = $moStudent->email;
        $moEntidad->fecha_nacimiento = $moStudent->fecha_nacimiento;
        $moEntidad->sexo = $moStudent->sexo;
        $moEntidad->dni = $moStudent->dni;
        $moEntidad->estado_marital = $moStudent->estado_marital;
        $moEntidad->grado_de_instruccion = $moStudent->grado_de_instruccion;
        $this->entidadRepository->actualizar( $estudiante->entity_id, $moEntidad);

        $estudiante->school_id = $this->escuelaRepository->registrarBuscarEscuela($moStudent->nombre_escuela)->id;
        $estudiante->graduation_year = $moStudent->anio_graduacion;
        $estudiante->save();
        return $estudiante->save();
    }

    public function buscarEstudiantePorDNI( string $dni)
    {
        return $this->entidadRepository->buscarEntidadPorDNI($dni);
    }

    public function getInformacionEstudiante( string $dni)
    {
        $entidadEstudiante = self::buscarEstudiantePorDNI( $dni);

        if (!$entidadEstudiante) return null;
        if (!$entidadEstudiante->student) return null;

        return  (object) [
            "idEstudiante" => $entidadEstudiante->student->id,
            "idEntity" => $entidadEstudiante->student->entity->id,
            "ieProcedencia" => $entidadEstudiante->student->school->name,
            "anioGraduacion" => $entidadEstudiante->student->graduation_year,
            "apPaterno" => $entidadEstudiante->father_lastname,
            "apMaterno" => $entidadEstudiante->mother_lastname,
            "nombre" => $entidadEstudiante->name,
            "direccion" => $entidadEstudiante->address,
            "distrito" => $entidadEstudiante->district->name,
            "telefono" => $entidadEstudiante->telephone,
            "fechaNacimiento" => $entidadEstudiante->birth_date,
            "sexo" => $entidadEstudiante->gender,
            "dni" => $entidadEstudiante->document_number,
            "photo_path" => $entidadEstudiante->photo_path,
        ];
    }

    public function eliminarEstudiante($idEstudiante)
    {
        $estudiante = self::find($idEstudiante);
        if ($estudiante) {
            $estudiante->delete();
            return true;
        }
        return false;
    }

    public function getListaAlumnos($parameto = "")
    {
        $listaAlumnos = Student::join('entities', 'entities.id', 'students.entity_id')
            ->where('entities.name', 'like', '%' . $parameto . '%')
            ->orWhere('entities.father_lastname', 'like', '%' . $parameto . '%')
            ->orWhere('entities.mother_lastname', 'like', '%' . $parameto . '%')
            ->orWhere('entities.document_number', 'like', '%' . $parameto . '%')
            ->select([
                "students.id as id ",
                "entities.father_lastname",
                "entities.mother_lastname",
                "entities.name",
                "entities.telephone",
                "entities.birth_date",
                "entities.gender",
                "entities.document_number",
                "students.graduation_year",
            ])->get();
        return $listaAlumnos;
    }
}
