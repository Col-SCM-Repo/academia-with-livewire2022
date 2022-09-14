<?php

namespace App\Repository;

use App\Enums\TipoDescuentosEnum;
use App\Enums\TiposBecasEnum;
use App\Models\Scholarship;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ScholarshipRepository extends Scholarship
{
    public function __construct()
    {
    }

    public function builderModel(){
        return (object)[
            ];
    }

   /*  public function becar(object $moBeca){
        if( $this->_matriculaRepository::find($moBeca->idMatricula) ){
            $beca = new Scholarship();
            $beca->enrollment_id = $moBeca->idMatricula;
            $beca->type_scholarship_id = $moBeca->tipoBeca_id;
            $beca->description = $moBeca->descripcion;
            $beca->user_id = Auth::user()->id;
            $beca->discount = self::calcularDescuento($moBeca->tipoBeca_id, $moBeca->costoCiclo, $moBeca->parametroDescuento);
            $beca->save();
            $beca->parameter_discount = $beca->typeScholarship->value ? $beca->typeScholarship->value : $moBeca->parametroDescuento ;
            $beca->save();
            return $beca;
        }
        throw new NotFoundResourceException('Error, no se encontro la matricula del alumno [COD_MAT: '.$moBeca->idMatricula.'].');
    } */

    /* public function editarBeca( int $idBeca, object $moBeca ){
        $beca = Scholarship::find($idBeca);
        if(!$beca) throw new NotFoundResourceException("Error, no se encontro la beca [Codigo: $idBeca]");

        $beca->type_scholarship_id = $moBeca->tipoBeca_id;
        $beca->description = $moBeca->descripcion;
        $beca->discount = self::calcularDescuento($moBeca->tipoBeca_id, $moBeca->costoCiclo, $moBeca->parametroDescuento);
        $beca->save();
        $beca->parameter_discount = $beca->typeScholarship->value ? $beca->typeScholarship->value : $moBeca->parametroDescuento ;
        $beca->save();
        return true ;
    }

    public function eliminarBeca( int $idBeca ){
        $beca = Scholarship::find($idBeca);
        if(!$beca) throw new NotFoundResourceException("Error, no se encontro la beca [Codigo: $idBeca]");
        $beca->delete();
        return true;
    } */

    public function listaDescuentos(){
        $descuentos = array();
        foreach (Scholarship::all() as $descuento)
            $descuentos [$descuento->id] = (object)[
                'id'=> $descuento->id,
                'tipo'=> $descuento->type_scholarship,
                'nombre'=> $descuento->description.' ('.$descuento->parameter_discount.')',
                'parametro'=> $descuento->parameter_discount,
            ];
        return $descuentos;
    }

    /* public function becasRegistradasMatricula( int $matriculaId ){
        $becas = array();
        foreach (Scholarship::where('enrollment_id', $matriculaId)->where('deleted_at', null)->get() as $beca ) {
            $becas [$beca->id] = [
                "id" => $beca->id,
                "nombre" => $beca->description,
                "tipo" => $beca->typeScholarship->name,
                "descuento" => $beca->discount,
                'fecha_creacion' => $beca->created_at,
            ];
        }
        return $becas ;
    } */

    public function calcularDescuento( int $descuento_id, $monto_evaluar){
       if(!$monto_evaluar) throw new Exception('Error, no se encontro monto a aplicar descuento');

       $descuento = Scholarship::find($descuento_id);
       if(!$descuento) throw new Exception('Error, no se encontro el descuento');

       switch($descuento->type_scholarship){
        case TipoDescuentosEnum::PORCENTAJE :   return round(round($monto_evaluar,2) * (1- ($descuento->parameter_discount)/100));
        case TipoDescuentosEnum::MONTO_FIJO :   return round($monto_evaluar,2) - round($descuento->parameter_discount);
        case TipoDescuentosEnum::OTRO :         return 0 ;
       }
        return 0;
    }

}
