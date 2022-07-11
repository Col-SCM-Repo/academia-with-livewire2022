<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function search_career()
    {
        $careers = Career::with(['group'])->where(DB::raw("UPPER(career)"), 'like', '%' . strtoupper($_GET['term']) . '%')->get();
        $response = array();
        foreach ($careers as $career) {

            $response[] = array('value' => $career->career, 'id' => $career->id);
        }
        return $response;
    }
}
