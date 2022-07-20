<?php

namespace App\Repository;

use App\Models\Entity;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class EntityRepository extends Entity
{
    private $distritoRepository;
    protected $table = "entities";

    public function __construct()
    {
        $this->distritoRepository = new DistrictRepository();
    }

    public function buscarEntidad($dni)
    {
        return Entity::where('document_number', $dni)->first();
    }

    public function registrarEntidad($obj)
    {
        $entidad = self::buscarEntidad($obj->dni);
        if (!$entidad) {
            $distrito = $this->distritoRepository->registraroBuscarDistrito($obj->distrito);
            $pais = 173;

            $entidad = new Entity();
            $entidad->document_number = $obj->dni;
            $entidad->birth_date = $obj->f_nac;
            $entidad->district_id = $distrito->id;
            $entidad->telephone = $obj->telefono;
            $entidad->mobile_phone = $obj->telefono;
            $entidad->address = $obj->direccion;
            $entidad->name = $obj->nombres;
            $entidad->father_lastname = $obj->ap_paterno;
            $entidad->mother_lastname = $obj->ap_materno;
            $entidad->document_type = 'dni';
            $entidad->gender = $obj->sexo;
            $entidad->marital_status = isset($obj->estado_marital) ? $obj->estado_marital : 'single';
            $entidad->country_id = $pais;
            $entidad->email = isset($obj->email) ? $obj->email : null;
            $entidad->instruction_degree = isset($obj->grado_instruccion) ? $obj->grado_instruccion : 'none';
            $entidad->save();
        }
        return $entidad;
    }

    public function actualizarEntidad($idEntidad, $objEntidad)
    {
        $entidad = Entity::find($idEntidad);
        if ($entidad) {
            $distrito = $this->distritoRepository->registraroBuscarDistrito($objEntidad->distrito);
            $pais = 173;

            $entidad->document_number = $objEntidad->dni;
            $entidad->birth_date = $objEntidad->f_nac;
            $entidad->district_id = $distrito->id;
            $entidad->telephone = $objEntidad->telefono;
            $entidad->mobile_phone = $objEntidad->telefono;
            $entidad->address = $objEntidad->direccion;
            $entidad->name = $objEntidad->nombres;
            $entidad->father_lastname = $objEntidad->ap_paterno;
            $entidad->mother_lastname = $objEntidad->ap_materno;
            $entidad->document_type = 'dni';
            $entidad->gender = $objEntidad->sexo;
            $entidad->marital_status = isset($objEntidad->estado_marital) ? $objEntidad->estado_marital : 'single';
            $entidad->country_id = $pais;
            $entidad->email = isset($objEntidad->email) ? $objEntidad->email : null;
            $entidad->instruction_degree = isset($objEntidad->grado_instruccion) ? $objEntidad->grado_instruccion : 'none';
            $entidad->save();
            return $entidad;
        }
        throw new NotFoundResourceException('Error, no se encontro al estudiante');
    }
}
