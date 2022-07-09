<?php

namespace App\EJB;
use App\Evidencia;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EvidenciaEJB extends Evidencia
{
    public function builderModelEJB()
    {
        $modelEJB = [
            'id' => 0,
            'incidente_id' => '0',
            'evidencia_descripcion' => null,	
            'path' => null,	
            'estado' => '0',	
            'user_id' => null
        ];
        return $modelEJB;
    }

    public function registrarEvidencias( $data ){
        $evidencia = new Evidencia();
        $evidencia->incidente_id = $data->incidente_id;
        $evidencia->evidencia_descripcion = $data->descripcion;
        $evidencia->estado = '0';
        $evidencia->user_id = Auth::user()->id;

        if ($data->hasFile('file_evidencia')) {
            $file = $data->file('file_evidencia');
            if ($file->isValid()) {
                $file_name = time() . '_' . $file->getClientOriginalName(). '.' . $file->getClientOriginalExtension();
                $file->move('uploads/evidencias', $file_name);

                $evidencia->path = $file_name;
            }else throw new BadRequestHttpException("No esun archivo valido");
        }else throw new BadRequestHttpException("No se envuentra ek archivo de la evidencia");   

        $evidencia->save();
        return $evidencia;
    }


}
