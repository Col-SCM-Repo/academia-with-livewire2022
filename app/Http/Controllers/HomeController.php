<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
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

    // reportes
    public function reportes()
    {
        return "reportes";
    }
}
