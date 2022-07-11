<?php

namespace App\EJB;

use App\Models\Incidente;
use Illuminate\Support\Facades\Auth;

class IncidenteEJB extends Incidente
{

    public function builderModelEJB()
    {
        $modelEJB = [
            'id' => 0,
            'enrollment_id' => 0,
            'auxiliar_id' => null,
            'secretaria_id' => null,
            'tipo_incidente' =>  '-',
            'descripcion' => '-',
            'justificacion' => null,
            'parentesco' => '-',
            'estado' => 0,
            'fecha_reporte' => null,
        ];
        return $modelEJB;
    }

    public function crearIncidente($obj)
    {
        //return $obj->all();
        // Si no es justificado
        $incidente  = new Incidente();
        $incidente->enrollment_id = $obj->id_enrollment;
        $incidente->auxiliar_id = Auth::user()->id;
        $incidente->tipo_incidente = $obj->tipo_incidente;
        $incidente->descripcion = $obj->text_incidente;
        $incidente->estado = '0';

        if ($obj->justificacion_estado == "1") {
            $incidente->estado = '1';
            $incidente->secretaria_id = Auth::user()->id;
            $incidente->justificacion = $obj->text_justificacion;
            $incidente->parentesco = $obj->parentesco;
            $incidente->fecha_reporte = date('Y-m-d H:i:s');
        }
        $incidente->save();

        return $incidente;
    }

    public function updateIncidente($obj, $id)
    {
        $incidente = Incidente::find($id);
        $estado_antiguo = $incidente->estado;

        //$incidente->auxiliar_id = Auth::user()->id ;
        $incidente->tipo_incidente = $obj->tipo_incidente;
        $incidente->descripcion = $obj->text_incidente;
        $incidente->estado = '0';

        if ($obj->justificacion_estado == '1') {
            //$incidente->secretaria_id = Auth::user()->id ;
            $incidente->justificacion = $obj->text_justificacion;
            $incidente->parentesco = $obj->parentesco;
            $incidente->estado = '1';
            //$incidente->fecha_reporte = date('Y-m-d H:i:s') ;
        }
        //Adicionales (Si ha cambiado el estado)
        if ($estado_antiguo != $incidente->estado) {
            if ($incidente->estado == '0') {
                $incidente->fecha_reporte = null;
                $incidente->secretaria_id = null;
                $incidente->justificacion = null;
                $incidente->parentesco = null;
            } else {
                $incidente->fecha_reporte = date('Y-m-d H:i:s');
                $incidente->secretaria_id = Auth::user()->id;
            }
        }
        $incidente->save();
        return $incidente;
    }
}
