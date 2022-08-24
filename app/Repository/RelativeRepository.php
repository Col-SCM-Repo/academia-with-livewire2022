<?php

namespace App\Repository;

use App\Models\Relative;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

// Relative es lo mismo que apoderados
class RelativeRepository extends Relative
{
    private $_entidadRepository, $ocupacionRepository;

    public function __construct()
    {
        $this->_entidadRepository = new EntityRepository();
        $this->ocupacionRepository = new OccupationRepository();
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

            // Propiedades de apoderado
            "estudiante_id" => null,            // student_id
            "parentesco" => null,               //  relative_relationship
            "ocupacion" => null,                //  occupation_id
        ];
    }

    public function buscarApoderadoPorDNI($dni)
    {
        $entidad = $this->_entidadRepository->buscarEntidadPorDNI($dni);
        if (!$entidad) return null;
        return $entidad->relative;
    }

    public function registrar( object $moApoderado)
    {
        if (!self::buscarApoderadoPorDNI($moApoderado->dni)) {
            $entidad = $this->_entidadRepository->buscarEntidadPorDNI($moApoderado->dni);
            if (!$entidad){
                $modelEntidad = $this->_entidadRepository->builderModelRepository();
                $modelEntidad->apellido_paterno = $moApoderado->apellido_paterno;
                $modelEntidad->apellido_materno = $moApoderado->apellido_materno;
                $modelEntidad->nombres = $moApoderado->nombres;
                $modelEntidad->direccion = $moApoderado->direccion;
                $modelEntidad->distrito = $moApoderado->distrito;
                $modelEntidad->telefono = $moApoderado->telefono;
                $modelEntidad->celular = $moApoderado->celular;
                $modelEntidad->email = $moApoderado->email;
                $modelEntidad->fecha_nacimiento = $moApoderado->fecha_nacimiento;
                $modelEntidad->sexo = $moApoderado->sexo;
                $modelEntidad->dni = $moApoderado->dni;
                $modelEntidad->estado_marital = $moApoderado->estado_marital;
                $modelEntidad->grado_de_instruccion = $moApoderado->grado_de_instruccion;
                $entidad = $this->_entidadRepository->registrar($modelEntidad);
            }
            $apoderado = new Relative();
            $apoderado->student_id	 = $moApoderado->estudiante_id;
            $apoderado->entity_id = $entidad->id;
            $apoderado->occupation_id = $this->ocupacionRepository->registrarBuscarOcupacion($moApoderado->ocupacion)->id;
            $apoderado->relative_relationship = $moApoderado->parentesco;
            $apoderado->save();
            return $apoderado;
        }
        throw new BadRequestException('Error, el apoderado ya se encuentra registrado');
    }

    public function actualizar( int $idRelacionApoderado, object $moApoderado)
    {
        $apoderado = Relative::find($idRelacionApoderado);
        if (!$apoderado) throw new NotFoundResourceException("No se encontrò el apoderado a actualizar");

        $modelEntidad = $this->_entidadRepository->builderModelRepository();
        $modelEntidad->apellido_paterno = $moApoderado->apellido_paterno;
        $modelEntidad->apellido_materno = $moApoderado->apellido_materno;
        $modelEntidad->nombres = $moApoderado->nombres;
        $modelEntidad->direccion = $moApoderado->direccion;
        $modelEntidad->distrito = $moApoderado->distrito;
        $modelEntidad->telefono = $moApoderado->telefono;
        $modelEntidad->celular = $moApoderado->celular;
        $modelEntidad->email = $moApoderado->email;
        $modelEntidad->fecha_nacimiento = $moApoderado->fecha_nacimiento;
        $modelEntidad->sexo = $moApoderado->sexo;
        $modelEntidad->dni = $moApoderado->dni;
        $modelEntidad->estado_marital = $moApoderado->estado_marital;
        $modelEntidad->grado_de_instruccion = $moApoderado->grado_de_instruccion;
        $this->_entidadRepository->actualizar( $apoderado->entity_id, $modelEntidad);

        $apoderado->occupation_id = $this->ocupacionRepository->registrarBuscarOcupacion($moApoderado->ocupacion)->id;
        $apoderado->relative_relationship = $moApoderado->parentesco;
        $apoderado->save();
        return $apoderado;
    }

    public function eliminar($dni)
    {
        $Apoderado = self::buscarApoderado($dni);
        if ($Apoderado) {
            $Apoderado->delete();
            return true;
        }
        return false;
    }

    public function getInformacionApoderado( string $dni, int $student_id = -1)
    {
        $entidad = $this->_entidadRepository->buscarEntidadPorDNI($dni);
        if ($entidad) {
            $apoderado = Relative::where('student_id', $student_id)->where('entity_id', $entidad->id)->first();
            if ($apoderado)
                return (object)[
                    "idRelacionApoderado" => $apoderado->id,
                    "idEntity" => $entidad->id,
                    "apPaterno" => $entidad->father_lastname,
                    "apMaterno" => $entidad->mother_lastname,
                    "nombre" => $entidad->name,
                    "direccion" => $entidad->address,
                    "distrito" => $entidad->district->name,
                    "telefono" => $entidad->telephone,
                    "fechaNacimiento" => $entidad->birth_date,
                    "sexo" => $entidad->gender,
                    "dni" => $entidad->document_number,
                    "estado_marital" => $entidad->marital_status,
                    "photo_path" => $entidad->photo_path,
                    "ocupacion" => $apoderado->occupation->name,
                    "parentesco" => $apoderado->relative_relationship,
                ];
            else
                return (object)[
                    "idRelacionApoderado" => null,
                    "idEntity" => $entidad->id,
                    "apPaterno" => $entidad->father_lastname,
                    "apMaterno" => $entidad->mother_lastname,
                    "nombre" => $entidad->name,
                    "direccion" => $entidad->address,
                    "distrito" => $entidad->district->name,
                    "telefono" => $entidad->telephone,
                    "fechaNacimiento" => $entidad->birth_date,
                    "sexo" => $entidad->gender,
                    "dni" => $entidad->document_number,
                    "estado_marital" => $entidad->marital_status,
                    "photo_path" => $entidad->photo_path,
                    "ocupacion" => null,
                    "parentesco" => null,
                ];
        }
        return null;
    }

    /* public function getListaApoderados()
    {
        $listaApoderados = array();
        foreach (Relative::all()  as $relacionApoderado)
            $listaApoderados[] = (object)[
                "idRelacionApoderado" => $relacionApoderado->id,
                "idEntity" => $relacionApoderado->entity->id,
                "apPaterno" => $relacionApoderado->entity->father_lastname,
                "apMaterno" => $relacionApoderado->entity->mother_lastname,
                "nombre" => $relacionApoderado->entity->name,
                "direccion" => $relacionApoderado->entity->address,
                "distrito" => $relacionApoderado->entity->district->name,
                "telefono" => $relacionApoderado->entity->telephone,
                "sexo" => $relacionApoderado->entity->gender,
                "dni" => $relacionApoderado->entity->document_number,
                "ocupacion" => $relacionApoderado->entity->occupation->name,
                "parentesco" => $relacionApoderado->relative_relationship,
            ];
        return $listaApoderados;
    } */
}
