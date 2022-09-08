<?php

namespace App\Repository;

use App\Enums\TiposBecasEnum;
use App\Models\Scholarship;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ScholarshipRepository extends Scholarship
{
    private $_matriculaRepository, $_tipoBecasRepository;

    public function __construct()
    {
        $this->_matriculaRepository = new EnrollmentRepository();
        $this->_tipoBecasRepository = new TypeScholarshipRepository();
    }

    public function builderModel(){
        return (object)[
            'idMatricula' => null,     // enrollment_id
            'tipoBeca_id' => null,     // type_scholarship_id
            'descripcion' => null,     // description
            'descuento' => null,       // discount

            'parametroDescuento' => null,
            'costoCiclo' => null
            ];
    }

    public function becar(object $moBeca){
        if( $this->_matriculaRepository::find($moBeca->idMatricula) ){
            $beca = new Scholarship();
            $beca->enrollment_id = $moBeca->idMatricula;
            $beca->type_scholarship_id = $moBeca->tipoBeca_id;
            $beca->description = $moBeca->descripcion;
            $beca->user_id = Auth::user()->id;
            $beca->discount = self::calcularDescuento($moBeca->tipoBeca_id, $moBeca->costoCiclo, $moBeca->parametroDescuento);
            $beca->save();
            return $beca;
        }
        throw new NotFoundResourceException('Error, no se encontro la matricula del alumno [COD_MAT: '.$moBeca->idMatricula.'].');
    }

    public function editarBeca( int $idBeca, object $moBeca ){
        $beca = Scholarship::find($idBeca);
        if(!$beca) throw new NotFoundResourceException("Error, no se encontro la beca [Codigo: $idBeca]");

        $beca->type_scholarship_id = $moBeca->tipoBeca_id;
        $beca->description = $moBeca->descripcion;
        $beca->discount = self::calcularDescuento($moBeca->tipoBeca_id, $moBeca->costoCiclo, $moBeca->parametroDescuento);
        $beca->save();
        return true ;
    }

    public function eliminarBeca( int $idBeca ){

    }

    public function listaTiposBecas(){
        $tiposBecas = array();
        foreach( $this->_tipoBecasRepository::all() as $tipoBecaIterador )
            $tiposBecas[$tipoBecaIterador->id] =  [
                "tipo_id" => $tipoBecaIterador->id,
                "nombre" => $tipoBecaIterador->name,
                "descripcion" => $tipoBecaIterador->description,
                "tipo" => TiposBecasEnum::getName($tipoBecaIterador->type),
                "valor" => $tipoBecaIterador->value,
                "edit" => $tipoBecaIterador->value? false:true
            ];
        return $tiposBecas;
    }

    public function calcularDescuento( int $tipoDescuentoId, $montoEvaluar, $parametroDescuento = null ){
        if(!$montoEvaluar) throw new Exception('Error, no se encontro monto a aplicar descuento');

        $tipoDescuento = $this->_tipoBecasRepository::find($tipoDescuentoId);
        if($tipoDescuento)
            switch ($tipoDescuento->type) {
                case TiposBecasEnum::PORCENTUAL_FIJO:       return round($montoEvaluar * $tipoDescuento->value /100);
                case TiposBecasEnum::PORCENTUAL_DINAMICO:   return round($montoEvaluar * $parametroDescuento->value /100);
                case TiposBecasEnum::MONTO_FIJO:            return round($parametroDescuento);
                case TiposBecasEnum::OTRO:                  return 0;
            }
        throw new NotFoundResourceException('Error, no se encontro el tipo de beca especificado');
    }

}
