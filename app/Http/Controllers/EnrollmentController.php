<?php

namespace App\Http\Controllers;

use App\EJB\EnrollmentEJB;
use App\EJB\PeriodoEJB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAlumnoRequest;
use App\Http\Requests\StoreApoderadoRequest;
use App\Http\Requests\StoreMatriculaRequest;
use App\Models\Period;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EnrollmentController extends Controller
{
    protected $enrollmentEJB;
    protected $periodEJB;

    public function __construct(PeriodoEJB $periodEJB, EnrollmentEJB $enrollmentEJB)
    {
        $this->periodEJB = $periodEJB;
        $this->enrollmentEJB = $enrollmentEJB;
    }

    public function main()
    {
        $get_value = null;

        if (isset($_GET['number'])) {
            $get_value = $_GET['number'];
        }
        return view('enrollments.main')->with('get_value', $get_value);
    }

    public function create()
    {
        return view('enrollments.create'); //->with('license_types',$license_types);
    }

    public function edit($id)
    {
        $enrollment = $this->enrollmentEJB->getDataEnrollemnt($id);

        // return $enrollment;
        return view('enrollments.edit')->with('enrollment', $enrollment)
            ->with('idEnrollment', $id);
    }

    public function getEnrollment(Request $request)
    {
        $enrollment = $this->enrollmentEJB->getDataEnrollemnt($request->input('id', 0));
        if (!$enrollment) throw new BadRequestHttpException("No existe el alumno");
        return response()->json($enrollment);
    }


    public function store(Request $request)
    {
        //return $request->all() ;
        $enrollment = $this->enrollmentEJB->createEnrollment($request);
        if ($enrollment)
            return  redirect()->action('EnrollmentController@edit', ['id' => $enrollment]);
        return null;
    }

    public function updateMatricula($id, StoreMatriculaRequest $request)
    {
        return $this->enrollmentEJB->updateMatricula($id, $request->input());
        return $request->all();
    }

    public function updateAlumno($matriculaId, StoreAlumnoRequest $request)
    {
        return $this->enrollmentEJB->updateAlumno($matriculaId, $request);
        return request()->json($this->enrollmentEJB->updateAlumno($matriculaId, $request));
    }

    public function updateApoderado($matriculaId, StoreApoderadoRequest $request)
    {
        return $this->enrollmentEJB->updateApoderado($matriculaId, $request);
        return request()->json($this->enrollmentEJB->updateApoderado($matriculaId, $request));
    }

    public function search_enrollment($param)
    {
        $enrollment = $this->enrollmentEJB->search_enrollment($param);
        return view('enrollments.partials.search_result')->with('enrollments', $enrollment);
    }


    public function cancel(Request $request)
    {
        return response()->json($this->cancel($request->id));
    }

    public function generate_random()
    {
        //Se define una cadena de caractares. 
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";

        $longitudCadena = strlen($cadena);
        $pass = "";
        $longitudPass = 10;

        //Creamos la contraseña
        for ($i = 1; $i <= $longitudPass; $i++) {
            $pos = rand(0, $longitudCadena - 1);
            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
    }


    public function period_enrollments_report()
    {
        $periods = Period::where('active', 1)->get();
        return view('reports.period_enrollments_report.index')->with('periods', $periods);
    }

    public function do_period_enrollments(Request $request)
    {
        // $enrollments=Enrollment::with(['classroom.level.period','student.entity'])
        //                         ->whereHas('classroom.level.period',function($query) use($request) {
        //                             $query->where('period_id',$request->period_id);
        //                         })->get();
        $period = Period::with(['levels.enrollments', 'levels.classrooms.enrollments', 'levels.level_type'])->whereId($request->period_id)->firstOrFail();
        return view('reports.period_enrollments_report.partials.listing')->with('period', $period); //->with('enrollments',$enrollments);
    }
}
