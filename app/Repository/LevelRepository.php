<?php

namespace App\Repository;

use App\Models\Level;
use App\Models\Type_code;
use Illuminate\Database\Eloquent\Collection;

class LevelRepository extends Level
{
    public function builderModelRepository()
    {
        return (object) [
            'id' => null,           // 
            'tipo_nivel' => null,   // type_id
            'periodo_id' => null,   // period_id
            'fecha_inicio' => null, // start_date
            'fecha_fin' => null,    // end_date
            'precio' => null,       // price
            'estado' => null,       // status  - 1 activo  /  0 inactivo
        ];
    }

    public function getNivelPorId( int $nivel_id ) : Level | null {
        return Level::find($nivel_id);
    }

    public function buscarNiveles( int $periodo_id ) : Collection{
        return Level::join('type_codes', 'levels.type_id', '=', 'type_codes.id')
        ->where('period_id', $periodo_id)
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

    public function generarNiveles ( int $periodo_id ) : bool {
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
            $nivel->start_date = $obj->fecha_inicio;
            $nivel->end_date = $obj->fecha_fin;
            $nivel->price = $obj->precio;
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
