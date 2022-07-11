<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function create()
    {
        return view('schools.partials.create');
    }

    public function store(Request $request)
    {
        $school = new School();
        $school->name = strtoupper($request->name);
        $school->address = 'xxxx';
        $school->district_id = $request->school_district_id;
        $school->country_id = 173;
        $school->save();

        $school_ = School::with('district')->whereId($school->id)->firstOrFail();

        return ['success' => true, 'school' => $school_];
    }

    public function search_ie()
    {
        $shools = School::with(['district'])->where(DB::raw("UPPER(name)"), 'like', '%' . strtoupper($_GET['term']) . '%')->get();
        $response = array();

        foreach ($shools as $school)
            $response[] = array('value' => $school->name . ' - ' . $school->district->name, 'id' => $school->id);

        return $response;
    }
}
