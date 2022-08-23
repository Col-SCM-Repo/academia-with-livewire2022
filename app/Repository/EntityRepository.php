<?php

namespace App\Repository;

use App\Models\Entity;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class EntityRepository extends Entity
{
    private $distritoRepository;
    protected $table = "entities";
    /*
        id  father_lastname  mother_lastname  name  address  district_id  telephone  mobile_phone  email
        birth_date  gender  country_id  document_type  document_number  marital_status  instruction_degree  photo_path
    */

    public function __construct()
    {
        $this->distritoRepository = new DistrictRepository();
    }

    public function buscarEntidadPorDNI($dni)
    {
        return Entity::where('document_number', $dni)->first();
    }

    public function builderModelRepository()
    {
        return (object) [
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
            //"ruta_foto" => "default.png",     //  photo_path
        ];
    }

    public function registrar( object $modelEntity)
    {
        $entidad = self::buscarEntidadPorDNI($modelEntity->dni);
        if (!$entidad) {
            $entidad = new Entity();
            $entidad->father_lastname = $modelEntity->apellido_paterno;
            $entidad->mother_lastname = $modelEntity->apellido_materno;
            $entidad->name = $modelEntity->nombres;
            $entidad->address = $modelEntity->direccion;
            $entidad->district_id = $this->distritoRepository->registraroBuscarDistrito($modelEntity->distrito)->id;
            $entidad->telephone = $modelEntity->telefono;
            $entidad->mobile_phone = $modelEntity->celular;
            $entidad->email = $modelEntity->email;
            $entidad->birth_date = $modelEntity->fecha_nacimiento;
            $entidad->gender = $modelEntity->sexo;
            $entidad->country_id = $modelEntity->pais_id;
            $entidad->document_type = $modelEntity->tipo_documento;
            $entidad->document_number = $modelEntity->dni;
            $entidad->marital_status = $modelEntity->estado_marital;
            $entidad->instruction_degree = $modelEntity->grado_de_instruccion;
            $entidad->save();
        }
        return $entidad;
    }

    public function actualizar(int $idEntidad, object $modelEntity)
    {
        $entidad = Entity::find($idEntidad);
        if (!$entidad)  throw new NotFoundResourceException('Error, no se encontrò persona (entidad) a actualizar');

        $entidad->father_lastname = $modelEntity->apellido_paterno ? $modelEntity->apellido_paterno :$entidad->father_lastname ;
        $entidad->mother_lastname = $modelEntity->apellido_materno ? $modelEntity->apellido_materno :$entidad->mother_lastname ;
        $entidad->name = $modelEntity->nombres ? $modelEntity->nombres :$entidad->name ;
        $entidad->address = $modelEntity->direccion ? $modelEntity->direccion :$entidad->address ;
        $entidad->district_id = $modelEntity->distrito? $this->distritoRepository->registraroBuscarDistrito($modelEntity->distrito)->id : $entidad->district_id;
        $entidad->telephone = $modelEntity->telefono ? $modelEntity->telefono :$entidad->telephone ;
        $entidad->mobile_phone = $modelEntity->celular ? $modelEntity->celular :$entidad->mobile_phone ;
        $entidad->email = $modelEntity->email ? $modelEntity->email :$entidad->email ;
        $entidad->birth_date = $modelEntity->fecha_nacimiento ? $modelEntity->fecha_nacimiento :$entidad->birth_date ;
        $entidad->gender = $modelEntity->sexo ? $modelEntity->sexo :$entidad->gender ;
        $entidad->country_id = $modelEntity->pais_id ? $modelEntity->pais_id :$entidad->country_id ;
        $entidad->document_type = $modelEntity->tipo_documento ? $modelEntity->tipo_documento :$entidad->document_type ;
        $entidad->document_number = $modelEntity->dni ? $modelEntity->dni :$entidad->document_number ;
        $entidad->marital_status = $modelEntity->estado_marital ? $modelEntity->estado_marital :$entidad->marital_status ;
        $entidad->instruction_degree = $modelEntity->grado_de_instruccion ? $modelEntity->grado_de_instruccion :$entidad->instruction_degree ;
        $entidad->save();
        return $entidad;
    }
}
