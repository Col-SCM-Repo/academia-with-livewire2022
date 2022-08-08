<?php

namespace App\Repository;

use App\Models\Level;
use App\Models\Type_code;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class LevelRepository extends Level
{
    public function builderModelRepository()
    {
        return (object) [
            'id' => null,           // 
            'tipo_nivel' => null,   // type_id
            'periodo_id' => null,   // period_id
            'fInicio' => null, // start_date
            'fFin' => null,    // end_date
            'costo' => null,       // price
            'estado' => null,       // status  - 1 activo  /  0 inactivo
        ];
    }

    public function getNivelPorId( int $nivel_id ) : Level | null {
        return Level::find($nivel_id);
    }

    public function buscarNiveles( int $periodo_id, int $estado = -5 ) : Collection{
        return Level::join('type_codes', 'levels.type_id', '=', 'type_codes.id')
        ->where('period_id', $periodo_id)
        ->where(function ( $query ) use ($estado){
            if($estado != -5){
                $query->where('levels.status', '=', $estado);
            }
        }, )
        ->select([
            'levels.id',
            'levels.period_id',
            'type_codes.description',
            'type_codes.type',
            'levels.start_date',
            'levels.end_date',
            'levels.price',
            'levels.status',
        ])->get();
    }

    public function buscarAulasPorNivel( int $periodo_id ) : array{
        $aulas = array();
        $aulasData = Level::join('type_codes', 'levels.type_id', '=', 'type_codes.id')
        ->leftJoin('classrooms', 'levels.id', '=', 'classrooms.level_id')        
        ->where('period_id', $periodo_id)
        ->where('levels.status', '=', 1)
        ->select(
            'levels.id AS level_id',
            'levels.period_id AS period_id',
            'type_codes.description',
            'type_codes.type',
            'levels.start_date',
            'levels.end_date',
            'levels.price',
            'levels.status',
            'classrooms.id AS classroom_id',
            'classrooms.name',
            'classrooms.vacancy',
        )->get();

        Log::debug($aulasData);
        /*
        'formularioAula.nombre' => 'required | string | min:1 ',
        'formularioAula.nivel_id' => 'required | integer | min:0 ',
        'formularioAula.vacantes' => 'required | integer | min:1 ',
        */

        $nombreNivel='';
        $aulasTemp = array();
        foreach ($aulasData as $aula) {
            $aulaTemp = [
                'id' => $aula->classroom_id,
                'nombre' => $aula->name,
                'nivel_id' => $aula->level_id,
                'vacantes' => $aula->vacancy,
                'costo' => $aula->price,
                'fInicio' => $aula->start_date,
                'fFin' => $aula->end_date,
                'periodo_id' => $aula->period_id,
            ];
            if( $nombreNivel == $aula->description ){
                $aulasTemp [] = $aulaTemp;
            }
            else{
                $nombreNivel =$aula->description;
                $aulas[ $nombreNivel ] = $aulasTemp; 
                $aulasTemp [] = array();
                if( $aula->classroom_id ){
                    $aulasTemp [] = $aulaTemp;
                }
                else{
                    $aulas[ $nombreNivel ] = $aulasTemp; 
                }
            }
        }

        if( count( $aulasTemp ) > 0){
                $aulas[ $nombreNivel ] = $aulasTemp; 
        }
        Log::debug($aulas);
        return $aulas;
    }


    public function informacionNivel( int $nivel_id ) : object{
        return Level::join('type_codes', 'levels.type_id', '=', 'type_codes.id')
        ->where('levels.id', $nivel_id)
        ->select([
            'levels.id',
            'levels.period_id',
            'type_codes.description',
            'type_codes.type',
            'levels.start_date',
            'levels.end_date',
            'levels.price',
            'levels.status',
        ])->first();
    }

    public function generarNiveles ( int $periodo_id) : bool {
        $nivelesGenerados = self::buscarNiveles($periodo_id);
        if(count($nivelesGenerados)==0){
            $nivelesAcademicos = Type_code::all();
            foreach ($nivelesAcademicos as $nivel) {
                $nivelGenerado = new Level();
                $nivelGenerado->type_id = $nivel->id;
                $nivelGenerado->period_id = $periodo_id;
                $nivelGenerado->start_date = null;
                $nivelGenerado->end_date = null;
                $nivelGenerado->price = null;
                $nivelGenerado->status = 0 ;
                $nivelGenerado->save();
            }
        }
        return false;
    }

    public function actualizarNivel (int $nivel_id, object $obj) : bool {
        $nivel = Level::find($nivel_id);
        if($nivel) {
            $nivel->start_date = $obj->fInicio;
            $nivel->end_date = $obj->fFin;
            $nivel->price = $obj->costo;
            $nivel->status = $obj->estado ;
            $nivel->save();
            return true;
        }
        return false;
    }

    public function cambiarEstado ( int $nivel_id, int $estado ) : bool {
        $nivel = Level::find($nivel_id);
        if($nivel){
            $nivel->status = $estado;
            return true;
        }
        return false;
    }
}
