<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Period;
use App\Level;
use App\Classroom;
use DB;

class ClassroomController extends Controller
{
    public function search_classroom(){
        $classrooms= Classroom::with(['level.period','level.level_type','enrollments'])
        
        ->withCount('enrollments') 

        ->whereHas("level.period", function($query){
            $query->where("active",1);
        })
        ->where(function ($query) {
            $query->where(DB::raw("UPPER(name)"),'like','%'.strtoupper($_GET['term']).'%')
            ->orWhereHas("level.level_type", function($q){
                $q->where(DB::raw("UPPER(description)"),'like','%'.strtoupper($_GET['term']).'%');
            })->orWhereHas("level.period", function($q){
                $q->where(DB::raw("UPPER(name)"),'like','%'.strtoupper($_GET['term']).'%');
            });
        })
        ->get();

        $response=array();

        foreach($classrooms as $classroom)
            $response[]=array('value'=>$classroom->level->period->name.' - '.$classroom->level->level_type->description.' - '.$classroom->name,'id'=>$classroom->id,'classroom'=>$classroom);

        return $response;
    }

    public function listing($level_id){
        $classrooms=Classroom::where('level_id',$level_id)->get();
        return view('classrooms.partials.listing')->with('classrooms',$classrooms);
    }


    public function destroy(Request $request){
        $classroom=Classroom::withCount('enrollments')->whereId($request->id)->firstOrFail();
        
        if($classroom->enrollments_count == 0){
            Classroom::destroy($request->id);
            return ['success'=>true,'level_id'=>$classroom->level_id];
        }else{
            return ['success'=>false];
        }
        
        
    }

    public function create($level_id){
        return view('classrooms.partials.create')->with('level_id',$level_id);
    }

    public function store(Request $request){
        $classroom=new Classroom();
        $classroom->name=$request->name;
        $classroom->vacancy=$request->vacancy;
        $classroom->level_id=$request->level_id;
        $classroom->save();

        return ['success'=>true,'level_id'=>$classroom->level_id];
    }

    public function edit($id){
        
    }

    public function update(Request $request){
        
    }

    public function classroom_per_level(){
        $classrooms=Classroom::where('level_id',$_GET['level_id'])->get();
        return $classrooms;
    }
}
