<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    //
    public function index()
    {
        return view('modulos.matricula.index');
    }
}
