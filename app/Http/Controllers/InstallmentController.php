<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

class InstallmentController extends Controller
{
    public function installments_control_view($enrollment_id)
    {
        $enrollment = Enrollment::with(['installments.payments'])->whereId($enrollment_id)->firstOrFail();
        return view('installments.partials.installments_control')->with('enrollment', $enrollment);
    }

    public function installmentsInfo($matriculaId)
    {
        $enrollment = Enrollment::with(['installments.payments'])->whereId($matriculaId)->firstOrFail();

        return view('installments.partials.installments_control')->with('enrollment', $enrollment);
        return $enrollment;
    }
}
