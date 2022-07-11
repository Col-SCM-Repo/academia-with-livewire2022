<?php

namespace App\Http\Controllers;

use App\Models\Evidencia;
use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EvidenciaController extends Controller
{
    public function index($id)
    {
        $incidente = Incidente::find($id);
        if (!$incidente) throw new NotFoundHttpException('No se encuentra el incidente');
        return view('incidentes.partials.evidencias')->with('evidencias', $incidente->evidencias);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {

        $request->validate([
            "id_incidente" => 'required | string | min:1'
        ]);

        //var_dump($request->all());
        $evidencia = new Evidencia();
        $evidencia->incidente_id = $request->id_incidente;
        $evidencia->evidencia_descripcion = $request->descripcion;
        $evidencia->estado = '0';
        $evidencia->user_id = Auth::user()->id;

        if ($request->hasFile('file_evidencia')) {
            $file = $request->file('file_evidencia');
            if ($file->isValid()) {
                $file_name = time() . '-evidencia' . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/evidencias', $file_name);
                $evidencia->path = $file_name;
            }
        } else
            echo "No se encontro al alumno";

        $evidencia->save();

        return $evidencia;
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $evidencia = Evidencia::find($id);
        if (!$evidencia) throw new NotFoundHttpException('No se ha econtrado la evidencia');
        $evidencia->delete();
        return $evidencia;
    }
}

/*
    Motivacion => Necesidades, carencia           (  )

*/
