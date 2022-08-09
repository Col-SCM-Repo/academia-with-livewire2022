<?php

namespace App\Repository;

use App\Models\Enrollment;
use App\Models\Entity;
use App\Models\Installment;
use App\Models\Payment;
use App\Models\Relative;
use App\Models\Secuence;
use App\Models\Student;
use Carbon\Carbon;
use DateTime;
use Error;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnrollmentRepository extends Enrollment
{
    protected $paymentRepository;

    public function __construct()
    {
        $this->paymentRepository = new PaymentRepository();
    }

    public function builderModelRepository()
    {
        $modelRepository = [
            'id' => 0,
            'code' => '0',
            'type' => 'normal',
            'student_id' => 0,
            'classroom_id' => 0,
            'relative_id' => 0,
            'relative_relationship' => 0,
            'user_id' => 0,
            'career_id' => 0,
            'paymennt_type' => 0,
            'fees_quantity' => 0,
            'period_cost' => 0,
            'cancelled' => 0,
            'observations' =>  '',
        ];
        return $modelRepository;
    }

    public function createEnrollment($data)
    {
    }


    public function updateMatricula($id, $request)
    {
    }
    public function updateAlumno($id, $alumnoUpdate)
    {
    }
    public function updateApoderado($id, $apoderadoUpdate)
    {
    }

    public function cancel($id)
    {
    }

    public function getDataEnrollemnt($id)
    {
    }

    // Busquedas
    public function search_enrollment($param)
    {
    }


    public function generate_random_password()
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
}
