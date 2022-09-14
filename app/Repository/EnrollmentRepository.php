<?php

namespace App\Repository;

use App\Enums\EstadosEnum;
use App\Enums\EstadosMatriculaEnum;
use App\Enums\FormasPagoEnum;
use App\Models\Enrollment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class EnrollmentRepository extends Enrollment
{
    private $_carrerasRepository, $_estudiantesReposiory;

    public function __construct()
    {
        $this->_carrerasRepository = new CareerRepository();
        $this->_estudiantesReposiory = new StudentRepository();
    }

    public function builderModelRepository()
    {
        return (object) [
            /*
                'tipo_matricula' => null,       // type
                'estudiante_id' => null,        // student_id
                'aula_id' => null,              // classroom_id
                'carrera' => null,              // nombre carrera
                'tipo_pago' => null,            // payment_type
                'observaciones' => null,        // observations

                'costo_matricula' => null,      //
                'costo_ciclo' => null,          // period_cost
                'cantidad_cuotas' => null,      // fees_quantity
                'cuotas_detalle' => null,       // fees_quantity
            */

            /* 'tipo_matricula' => null,       // type */
            'descuento_id' => null,         // scholarship_id
            'estudiante_id' => null,        // student_id
            'aula_id' => null,              // classroom_id
            'carrera' => null,              // nombre carrera
            'costo_ciclo' => null,          // period_cost
            'costo_ciclo_final' => null,    // period_cost_final
            'observaciones' => null,        // observations
        ];
    }

    public function registrar( object $mEnrollment)
    {
        if (self::alumnoEstaMatriculado($mEnrollment->aula_id, $mEnrollment->estudiante_id))
            throw new BadRequestException('El alumno ya se encuentra matriculado');

        $matricula = new Enrollment();
        $matricula->type = 'normal';
        $matricula->student_id = $mEnrollment->estudiante_id;
        $matricula->classroom_id = $mEnrollment->aula_id;
        $matricula->user_id = Auth::user()->id;
        $matricula->career_id = $this->_carrerasRepository->buscarRegistrarCarrera($mEnrollment->carrera)->id;
        $matricula->scholarship_id = $mEnrollment->descuento_id;
        $matricula->period_cost = $mEnrollment->costo_ciclo;
        $matricula->period_cost_final = $mEnrollment->costo_ciclo_final;
        $matricula->amount_paid = 0;
        $matricula->observations = $mEnrollment->observaciones;
        $matricula->save();
        $matricula->code = str_pad($matricula->id, 6, "0", STR_PAD_LEFT);
        $matricula->save();
        return $matricula;
        /*
            $matricula->fees_quantity = ($mEnrollment->tipo_pago == strtoupper(FormasPagoEnum::CREDITO)) ? $mEnrollment->cantidad_cuotas : 0;
            $matricula->payment_type = $mEnrollment->tipo_pago;
            // Cuotas de pago (Installments)
            $mIntallment = $this->_cuotasRepository->builderModelRepository();
            $mIntallment->matricula_id = $matricula->id;
            $mIntallment->tipo_pago = $mEnrollment->tipo_pago;
            $mIntallment->costo_matricula = $mEnrollment->costo_matricula;
            $mIntallment->costo_ciclo = $mEnrollment->costo_ciclo;
            $mIntallment->cuotas = $matricula->fees_quantity;
            $mIntallment->detalle_cuotas = $matricula->fees_quantity >0 ? $mEnrollment->cuotas_detalle : array() ;

            $cuotasGeneradas = null;
            try {
                $cuotasGeneradas = $this->_cuotasRepository->generarCoutasPago($mIntallment);
                if(! $cuotasGeneradas) throw new Exception('Ocurrio un error al generar las cuotas de pago');
                return $matricula;
            } catch (Exception $e) {
                self::eliminar( $matricula->id );
                throw new BadRequestException($e->getMessage());
            }
        */
    }

    public function actualizar( int $matricula_id, object $mEnrollment)
    {
        $matricula = Enrollment::find($matricula_id);
        if(!$matricula) throw new NotFoundResourceException('Error, no se encontrò la matricula');

        $matricula->type = 'normal';
        $matricula->classroom_id = $mEnrollment->aula_id;
        $matricula->career_id = $this->_carrerasRepository->buscarRegistrarCarrera($mEnrollment->carrera)->id;
        $matricula->scholarship_id = $mEnrollment->descuento_id;
        $matricula->period_cost = $mEnrollment->costo_ciclo;
        $matricula->period_cost_final = $mEnrollment->costo_ciclo_final;
        $matricula->observations = $mEnrollment->observaciones;
        $matricula->status = EstadosMatriculaEnum::PENDIENTE_ACTIVACION;
        $matricula->save();
        return $matricula;
    }

    public function eliminar( int $matricula_id, bool $automatico = false)
    {
        $matricula = Enrollment::find($matricula_id);
        if(!$matricula) throw new NotFoundResourceException('Error, no se encontrò la matricula');

        if($automatico){
            $matricula->observations = "Autoeliminacion" ;
            $matricula->delete();
        }else{
            $matricula->status = EstadosMatriculaEnum::INACTIVO;
            $matricula->save();
        }
        return true;
    }

    public function matriculados(int $aula_id)
    {
    }

    public function buscar(string $dni_estudiante)
    {
    }

    public function informacionDeMatricula(int $estudiante_id)
    {
    }

    public function alumnoEstaMatriculado(int $aula_id, int $estudiante_id)
    {
        $matriculaAlumno = self::where('student_id', $estudiante_id)
            ->where('classroom_id', $aula_id)
            ->where('deleted_at', null)
            ->where('status', '!=', EstadosMatriculaEnum::INACTIVO)
            ->first();
        return $matriculaAlumno;
    }

    public function buscarAlumnos( string $parametro ){
        /* $estudiantes = $this->_estudiantesReposiory::all()
        $listaAlumnos = array(); */



    }

    public function buscarMatriculaVigente( int $estudiante_id, int $periodo_id ){
        return Enrollment::join('classrooms', 'enrollments.classroom_id', 'classrooms.id')
                                ->join('levels', 'classrooms.level_id' ,'levels.id')
                                ->where('enrollments.deleted_at', null)
                                ->where('classrooms.deleted_at', null)
                                ->where('enrollments.student_id', $estudiante_id)
                                ->where('levels.period_id', $periodo_id)
                                ->select(   'enrollments.id as id',
                                            'enrollments.student_id as student_id',
                                            'enrollments.status as status',
                                            'enrollments.status as status',
                                             )
                                ->first();
    }

    public function listaMatriculasEstudiante( int $estudianteId ){

        $matriculas = array();
        foreach (Enrollment::where('student_id', $estudianteId)->get() as $matricula) {
            if($matricula->classroom->level->period->active == EstadosEnum::ACTIVO)
                $estadoMatricula = ($matricula->status == EstadosEnum::ACTIVO)? "ACTIVO" : "RETIRADO";
            else
                $estadoMatricula = ($matricula->status == EstadosEnum::ACTIVO)? "INACTIVO" : "RETIRADO";

            $matriculas[] = (object)[
                'matricula_id' => $matricula->id,
                'matricula_codigo' => $matricula->code,
                'estudiante_id' => $matricula->student_id,
                'aula' => $matricula->classroom->name,
                'nivel' => $matricula->classroom->level->level_type->description,
                'periodo' => $matricula->classroom->level->period->name,
                'anio' => $matricula->classroom->level->period->year,
                'descripcion'=> $matricula->classroom->level->period->name.'/'.$matricula->classroom->level->level_type->description.'/'.$matricula->classroom->name,
                'estado_matricula' => $estadoMatricula,
                'fecha'=>date ( 'Y-m-d h:i:s A', strtotime($matricula->created_at))
            ];
        }
        return $matriculas;
    }

}
