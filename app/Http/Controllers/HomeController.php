<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('index');
    }

    // matriculas
    public function matriculas()
    {
        return "matriculas";
    }

    // mantenimiento
    public function mantenimiento()
    {
        return "mantenimiento";
    }
    
    public function ciclosAulas (){
        return view('modulos.ciclosyAulas.main');
    }

    // reportes
    public function reportes()
    {
        return "reportes";
    }
}
