<?php

namespace App\Repository;

use App\Models\Relative;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

// Relative es lo mismo que decir apoderado
class RelativeRepository extends Relative
{
    private $entidadRepositorio, $ocupacionRepository;

    public function __construct()
    {
        $this->entidadRepositorio = new EntityRepository();
        $this->ocupacionRepository = new OccupationRepository();
    }

    public function buscarApoderado($dni)
    {
        $entidad = $this->entidadRepositorio->buscarEntidad($dni);
        if (!$entidad) return null;
        return $entidad->relative;
    }

    public function registrarApoderado($objApoderado)
    {
        $apoderado = self::buscarApoderado($objApoderado->dni);
        if (!$apoderado) {
            $entidad = $this->entidadRepositorio->buscarEntidad($objApoderado->dni);
            if (!$entidad)
                $entidad = $this->entidadRepositorio->registrarEntidad($objApoderado);

            $ocupacion = $this->ocupacionRepository->registrarBuscarOcupacion($objApoderado->ocupacion);

            $Apoderado = new Relative();
            $Apoderado->entity_id = $entidad->id;
            $Apoderado->occupation_id = $ocupacion->id;
            $Apoderado->save();
            return $Apoderado;
        }
        return null;
    }

    public function actualizarApoderado($idRelacionApoderado, $objApoderado)
    {
        $apoderado = Relative::find($idRelacionApoderado);

        if (!$apoderado) throw new NotFoundResourceException("No se encontro el apoderado");

        $ocupacion = $this->ocupacionRepository->registrarBuscarOcupacion($objApoderado->ocupacion);
        $apoderado->occupation_id = $ocupacion->id;

        $this->entidadRepositorio->actualizarEntidad($apoderado->entity_id, $objApoderado);

        $apoderado->save();
        return $apoderado;
    }

    public function eliminarApoderado($dni)
    {
        $Apoderado = self::buscarApoderado($dni);
        if ($Apoderado) {
            $Apoderado->delete();
            return true;
        }
        return false;
    }

    public function getInformacionApoderado($dni)
    {
        $entidad = $this->entidadRepositorio->buscarEntidad($dni);
        $apoderado = $entidad ? $entidad->relative : null;

        if ($apoderado) {
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
            ];
        }
        return null;
    }

    public function getListaApoderados()
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
            ];
        return $listaApoderados;
    }
}
