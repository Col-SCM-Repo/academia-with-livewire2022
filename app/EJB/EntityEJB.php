<?php

namespace App\EJB;

use App\Models\Entity;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EntityEJB extends Entity
{
    private $distritoEjb;

    public function __construct()
    {
        $this->distritoEjb = new DistrictEJB();
    }

    public function buscarEntidad($dni)
    {
        return Entity::where('document_number', $dni)->first();
    }

    public function registrarEntidad($obj)
    {
        $entidad = self::buscarEntidad($obj->dni);
        if (!$entidad) {
            $distrito = $this->distritoEjb->registraroBuscarDistrito($obj->distrito);
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
            $entidad->marital_status = $obj->estado_marital ? $obj->estado_marital : 'single';
            $entidad->country_id = $pais;
            $entidad->email = $obj->email ? $obj->email : null;
            $entidad->instruction_degree = $obj->grado_instruccion ? $obj->grado_instruccion : 'none';
            /* falta 
                        PAIS    ** Estudiante
                        SEXO
                        email   ** apoderado
            */
            // $entidad->photo_path = null;
            $entidad->save();
        }
        return $entidad;
    }
}


/*
    // Buscar o registrar ie_procedencia
    // Buscar o registrar pais
*/
