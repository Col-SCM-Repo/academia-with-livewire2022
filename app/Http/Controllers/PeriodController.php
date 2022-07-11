<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Period;
use App\Models\Type_code;
use App\Models\Level;
use App\Models\Classroom;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::all()->sortByDesc('active');
        return view('periods.index')->with('periods', $periods);
    }

    public function listing()
    {
        $periods = Period::all()->sortByDesc('active');
        return view('periods.partials.listing')->with('periods', $periods);
    }

    public function create()
    {
        $level_types = Type_code::where('type', 'level_type')->get();
        return view('periods.create')->with('level_types', $level_types);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $period = new Period();
            $period->name = $request->name;
            $period->year = $request->year;
            $period->save();

            if ($request->checks) {

                $datetime = date('Y-m-d H:i:s');
                $levels = [];
                foreach ($request->checks as $key => $check) {
                    $start_date = Carbon::createFromFormat('d/m/Y', $request->start_date[$key])->toDateString();
                    $end_date = Carbon::createFromFormat('d/m/Y', $request->end_date[$key])->toDateString();
                    $levels[] = ['type_id' => $check, 'period_id' => $period->id, 'start_date' => $start_date, 'end_date' => $end_date, 'price' => $request->price[$key], 'created_at' => $datetime, 'updated_at' => $datetime];
                }

                Level::insert($levels);
            }
            DB::commit();
            return redirect()->action('PeriodController@edit', ['id' => $period->id]);
            // return redirect()->action('PeriodController@index');
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function edit($id)
    {
        // $id=$_GET['id'];
        // $id=$request->route('id');
        $period = Period::with(['levels.level_type', 'levels.classrooms', 'levels.enrollments'])->whereId($id)->firstOrFail();
        $level_types = Type_code::where('type', 'level_type')->get();
        // return $period;
        return view('periods.edit')->with('period', $period)->with('level_types', $level_types);
    }

    public function update(Request $request)
    {

        // return $request;

        DB::beginTransaction();

        try {

            // Eliminamos los niveles que han sido unchecked
            if ($request->deleted_level_id) {
                Classroom::whereIn('level_id', $request->deleted_level_id)->delete();
                // Level::whereIn('id',$request->deleted_level_id)->delete();
                Level::destroy($request->deleted_level_id);
            }

            //Actualizamos la info de los niveles que persisten
            if ($request->level_id) {
                foreach ($request->level_id as $key => $id) {
                    $start_date = Carbon::createFromFormat('d/m/Y', $request->edit_start_date[$key])->toDateString();
                    $end_date = Carbon::createFromFormat('d/m/Y', $request->edit_end_date[$key])->toDateString();
                    Level::whereId($id)->update(['start_date' => $start_date, 'end_date' => $end_date, 'price' => $request->edit_price[$key]]);
                }
            }

            //Creamos los nuevos niveles
            if ($request->level_type_id) {
                $datetime = date('Y-m-d H:i:s');
                $levels = [];
                foreach ($request->level_type_id as $key => $id) {
                    $start_date = Carbon::createFromFormat('d/m/Y', $request->start_date[$key])->toDateString();
                    $end_date = Carbon::createFromFormat('d/m/Y', $request->end_date[$key])->toDateString();
                    $levels[] = ['type_id' => $id, 'period_id' => $request->period_id, 'start_date' => $start_date, 'end_date' => $end_date, 'price' => $request->price[$key], 'created_at' => $datetime, 'updated_at' => $datetime];
                }

                Level::insert($levels);
                // dd($levels);
            }

            DB::commit();

            // return ['success'=>true];

            return redirect()->action('PeriodController@index');
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }



    public function status(Request $request)
    {

        DB::beginTransaction();

        try {

            // DB::statement("UPDATE periods set active=0 where id!=0;");

            // Period::where('id', '!=' ,0)->update(['active' => 0]);

            $period = Period::find($request->id);
            $period->active = $request->status;
            $period->save();

            DB::commit();

            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false];
        }
    }

    public function level_per_period()
    {
        $levels = Level::with('level_type')->where('period_id', $_GET['period_id'])->get();
        return $levels;
    }

    /* API */
    public function nivelesGet()
    {
        return Type_code::all();
    }

    public function aulasGet($id)
    {

        $classrooms = [];
        $levels = Level::where('type_id', $id)->get();
        foreach ($levels as $level) {
        }
        return [...$classrooms];
    }
}
