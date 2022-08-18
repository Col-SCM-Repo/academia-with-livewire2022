<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Enums\FormasPagoEnum;
use App\Models\Enrollment;
use App\Models\Installment;
use Illuminate\Support\Facades\Auth;

class EnrollmentRepository extends Enrollment
{
    protected $_cuotasRepository, $_carrerasRepository;

    /*
        Cancelado::estados  (  activo ! 1 cancelado)
        Campos tabla enrollment:
            id, code, type, student_id, classroom_id, relative_id, relative_relationship, user_id,
            career_id, payment_type, fees_quantity, period_cost, cancelled, observations
    */
    public function __construct()
    {
        $this->_cuotasRepository = new InstallmentRepository();
        $this->_carrerasRepository = new CareerRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            //'id' => null,                  // id
            //'codigo' => null,              // code
            'tipo_matricula' => null,       // type
            'estudiante_id' => null,        // student_id
            'aula_id' => null,              // classroom_id
            'apoderado_id' => null,         // relative_id
            'relacion_apoderado' => null,   // relative_relationship
            //'usuario_id' => null,         // user_id
            'carrera' => null,           // career_id
            'tipo_pago' => null,            // payment_type
            'cantidad_cuotas' => null,      // fees_quantity
            'costo_matricula' => null,      // --------------------
            'costo_ciclo' => null,          // period_cost
            //estado' => null,              // status
            'observaciones' => null,        // observations
        ];
    }

    public function registrarMatricula($modelEnrollment)
    {
        if (self::alumnoEstaMatriculado($modelEnrollment->aula_id, $modelEnrollment->estudiante_id)) return null;

        $matricula = new Enrollment();
        $matricula->type = $modelEnrollment->tipo_matricula;
        $matricula->student_id = $modelEnrollment->estudiante_id;
        $matricula->classroom_id = $modelEnrollment->aula_id;
        $matricula->relative_id = $modelEnrollment->apoderado_id;
        $matricula->relative_relationship = $modelEnrollment->relacion_apoderado;
        $matricula->user_id = Auth::user()->id;

        // buscar a la carrera
        $carrera = $this->_carrerasRepository->buscarCarrera($modelEnrollment->carrera) ;

        $matricula->career_id = $carrera? $carrera->id : 666;
        $matricula->payment_type = $modelEnrollment->tipo_pago;
        $matricula->fees_quantity = ($modelEnrollment->tipo_pago == strtoupper(FormasPagoEnum::CREDITO)) ? $modelEnrollment->cantidad_cuotas : 0;
        $matricula->period_cost = $modelEnrollment->costo_ciclo;
        $matricula->status = EstadosEnum::ACTIVO;
        $matricula->observations = $modelEnrollment->observaciones;
        $matricula->save();

        $matricula->code = str_pad($matricula->id, 6, "0", STR_PAD_LEFT);
        $matricula->save();

        // generar cuotas de pago
        $modelInstallment = $this->_cuotasRepository->builderModelRepository();
        $modelInstallment->matricula_id = $matricula->id;
        $modelInstallment->tipo_pago = $modelEnrollment->tipo_pago;
        $modelInstallment->costo_matricula = $modelEnrollment->costo_matricula;
        $modelInstallment->costo_ciclo = $modelEnrollment->costo_ciclo;
        $modelInstallment->cuotas = $matricula->fees_quantity;

        return $this->_cuotasRepository->generarCoutasPago($modelInstallment) ? $matricula : null;
    }

    public function actualizarMatricula($matricula_id, $modelEnrollment)
    {
    }

    public function eliminarMatricula($matricula_id, $modelEnrollment)
    {
    }

    public function listaMatriculados($aula_id)
    {
    }

    public function historialMatriculas($aula_id)
    {
    }

    public function buscarMatricula(string $dni_estudiante)
    {
    }

    public function informacionDeMatricula(int $estudiante_id, string $dni_estudiante)
    {
    }

    public function alumnoEstaMatriculado(int $aula_id, int $estudiante_id)
    {
        $matriculaAlumno = self::where('student_id', $estudiante_id)
            ->where('classroom_id', $aula_id)
            ->where('deleted_at', null)
            ->where('status', EstadosEnum::ACTIVO)
            ->first();
        return $matriculaAlumno;
    }
}
