<?php

namespace App\Repository;

use App\Models\Student;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class StudentRepository extends Student
{
    private $entidadRepository, $escuelaRepository;

    public function __construct()
    {
        $this->entidadRepository = new EntityRepository();
        $this->escuelaRepository = new SchoolRepository();
    }

    public function buscarEstudiante($identificadorEstudiante, $isDNI = true)
    {
        $entidadAlumno =  null;
        if ($isDNI)
            $entidadAlumno = $this->entidadRepository->buscarEntidad($identificadorEstudiante);
        else
            $entidadAlumno = $this->entidadRepository::find($identificadorEstudiante);
        return  $entidadAlumno;
    }

    public function getInformacionEstudiante($dni)
    {
        $entidadEstudiante = self::buscarEstudiante($dni);

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

    public function registrarEstudiante($objEstudiante, $entidad)
    {
        if (!$entidad) throw new NotFoundResourceException("Error, No se encontro a la entidad");
        $estudiante = $this::where('entity_id', $entidad->id)->first();

        if (!$estudiante) {
            $escuela = $this->escuelaRepository->registrarBuscarEscuela($objEstudiante->Ie_procedencia);

            $estudiante = new Student();
            $estudiante->entity_id = $entidad->id;
            $estudiante->school_id = $escuela->id;
            $estudiante->graduation_year = $objEstudiante->anio_egreso;
            $estudiante->save();
            $estudiante->creado = true;
        } else
            $estudiante->creado = false;
        return $estudiante;
    }

    public function actualizarEstudiante($objEstudiante, $idEstudiante)
    {
        $estudiante = Student::find($idEstudiante);
        if (!$estudiante) throw new NotFoundResourceException('Error, no se encontro al estudiante');

        $escuela = $this->escuelaRepository->registrarBuscarEscuela($objEstudiante->Ie_procedencia);

        $estudiante->school_id = $escuela->id;
        $estudiante->graduation_year = $objEstudiante->anio_egreso;

        $entidadActualizada = $this->entidadRepository->actualizarEntidad($estudiante->entity_id, $objEstudiante);
        $estudiante->save();

        return $entidadActualizada ? true : false;
    }

    public function eliminarEstudiante($idEstudiante)
    {
        $estudiante = self::buscarEstudiante($idEstudiante, false);
        if ($estudiante) {
            $estudiante->delete();
            return true;
        }
        return false;
    }
}
