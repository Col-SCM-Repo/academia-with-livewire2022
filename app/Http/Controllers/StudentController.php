<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Period;

class StudentController extends Controller
{
    //

    public function search_student()
    {
        $students = Student::with(['entity.district', 'school.district'])->whereHas("entity", function ($q) {
            $q->where("document_number", 'like', '%' . $_GET['term'] . '%');
        })->get();
        $response = array();

        foreach ($students as $student)
            $response[] = array('value' => $student->entity->document_number, 'id' => $student->id, 'student' => $student);
        return $response;
    }



    public function classroom_students_report()
    {
        $periods = Period::where('active', 1)->get();
        return view('reports.classroom_student_report.index')->with('periods', $periods);
    }

    public function do_classroom_students(Request $request)
    {
        $students = Student::with(['entity.district', 'enrollment'])
            ->whereHas('enrollment', function ($q) use ($request) {
                $q->where('classroom_id', $request->classroom_id);
            })->get();
        // return $students;
        return view('reports.classroom_student_report.partials.listing')->with('students', $students);
    }
}
