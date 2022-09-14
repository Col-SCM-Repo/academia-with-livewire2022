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
    public function builderModel(){
        return (object)[
            'tipoDescuento' => null,        // type_scholarship
            'descripcion' => null,          // description
            'parametroDescuento' => null,   // parameter_discount
            ];
    }

    public function registrar( object $modelDescuento ){
        $descuento = new Scholarship();
        $descuento->type_scholarship = $modelDescuento->tipoDescuento ;
        $descuento->description = $modelDescuento->descripcion ;
        $descuento->parameter_discount = $modelDescuento->parametroDescuento ;
        $descuento->save();
        return $descuento;
    }

    public function actualizar( int $descuentoId, object $modelDescuento ){
        $descuento = Scholarship::find($descuentoId);
        if(!$descuento) throw new NotFoundResourceException('Error, no se encontro el descuento a modificar');
        $descuento->type_scholarship = $modelDescuento->tipoDescuento ;
        $descuento->description = $modelDescuento->descripcion ;
        $descuento->parameter_discount = $modelDescuento->parametroDescuento ;
        $descuento->save();
        return $descuento;
    }

    public function eliminar(int $descuentoId){
        $descuento = Scholarship::find($descuentoId);
        if(!$descuento) throw new NotFoundResourceException('Error, no se encontro el descuento a eliminar');
        $descuento->delete();
        return true;
    }

    public function listaDescuentos(){
        $descuentos = array();
        foreach (Scholarship::all() as $descuento)
            $descuentos [$descuento->id] = (object)[
                'id'=> $descuento->id,
                'tipo'=> TipoDescuentosEnum::getName($descuento->type_scholarship),
                'nombre'=> $descuento->description.' ('.$descuento->parameter_discount.')',
                'descripcion'=> $descuento->description,
                'parametro'=> $descuento->parameter_discount,
            ];
        return $descuentos;
    }

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
