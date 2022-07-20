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
        DB::beginTransaction();

        try {

            $enrollment = new Enrollment();
            $enrollment->type = $data->type;
            $enrollment->classroom_id = $data->classroom_id;
            $enrollment->relative_relationship = $data->relative_relationship;
            $enrollment->user_id = Auth::user()->id;
            $enrollment->career_id = $data->career_id;
            $enrollment->payment_type = $data->payment_type;
            if ($enrollment->payment_type == 'credit') {
                $enrollment->fees_quantity = $data->fees_quantity;
            }
            $enrollment->period_cost = $data->period_cost;
            $enrollment->observations = $data->observations;

            if ($data->student_id == 0) {
                $entity = Entity::where('document_number', $data->student_document_number)->first();

                if ($entity == null) {

                    $entity = new Entity();
                    $entity->document_type = 'dni';
                    $entity->document_number = $data->student_document_number;

                    $entity->father_lastname = $data->student_father_lastname;
                    $entity->mother_lastname = $data->student_mother_lastname;
                    $entity->name = $data->student_name;

                    // $entity->birth_date=$data->student_birth_date;
                    $entity->birth_date = Carbon::createFromFormat('d/m/Y', $data->student_birth_date)->toDateString();
                    $entity->telephone = $data->student_telephone;


                    $entity->address = $data->student_address;
                    $entity->district_id = $data->student_district_id;

                    $entity->save();
                }

                $student = new Student();
                $student->entity_id = $entity->id;
                $student->school_id = $data->student_ie_id;
                $student->graduation_year = $data->student_graduation_year;

                if ($data->hasFile('student_photo_file')) {
                    $file = $data->file('student_photo_file');
                    if ($file->isValid()) {
                        $file_name = time() . '_' . $this->generate_random() . '.' . $file->getClientOriginalExtension();
                        $file->move('uploads/photo_files', $file_name);

                        $student->photo_file = $file_name;
                    }
                } else {
                    $student->photo_file = 'avatar_default.png';
                }

                $student->save();
                $enrollment->student_id = $student->id;
            } else {
                $enrollment->student_id = $data->student_id;
            }

            if ($data->relative_id == 0) {
                $entity = Entity::where('document_number', $data->relative_document_number)->first();

                if ($entity == null) {

                    $entity = new Entity();
                    $entity->document_type = 'dni';
                    $entity->document_number = $data->relative_document_number;

                    $entity->father_lastname = $data->relative_father_lastname;
                    $entity->mother_lastname = $data->relative_mother_lastname;
                    $entity->name = $data->relative_name;

                    // $entity->birth_date=$data->relative_birth_date;
                    $entity->birth_date = Carbon::createFromFormat('d/m/Y', $data->relative_birth_date)->toDateString();
                    $entity->telephone = $data->relative_telephone;

                    $entity->address = $data->relative_address;

                    $entity->save();
                }

                $relative = new Relative();
                $relative->entity_id = $entity->id;
                $relative->occupation = $data->relative_occupation;



                $relative->save();

                $enrollment->relative_id = $relative->id;
            } else {
                $enrollment->relative_id = $data->relative_id;
            }

            $enrollment->save();
            $code = str_pad($enrollment->id, 6, "0", STR_PAD_LEFT);
            $enrollment->code = $code;
            $enrollment->save();

            $installments = [];
            if ($enrollment->payment_type == 'credit') {
                $installments[] = ['enrollment_id' => $enrollment->id, 'order' => 0, 'type' => 'enrollment', 'amount' => $data->enrollment_cost];
                $fee_cost = $enrollment->period_cost / $enrollment->fees_quantity;
                for ($i = 0; $i < $enrollment->fees_quantity; $i++) {
                    $installments[] = ['enrollment_id' => $enrollment->id, 'order' => ($i + 1), 'type' => 'installment', 'amount' => $fee_cost];
                }
            } else {
                $installments[] = ['enrollment_id' => $enrollment->id, 'order' => 0, 'type' => 'enrollment', 'amount' => 0.00];
                $installments[] = ['enrollment_id' => $enrollment->id, 'order' => 1, 'type' => 'installment', 'amount' => $enrollment->period_cost];
            }

            Installment::insert($installments);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return null;
        }
        return $enrollment;
    }


    public function updateMatricula($id, $request)
    {
        DB::beginTransaction();
        try {
            $enrollment = Enrollment::find($id);
            if ($enrollment) {
                $enrollment->career_id = $request['career_id'];
                $enrollment->classroom_id = $request['classroom_id'];
                $enrollment->observations = $request['observations'];
                $enrollment->type = $request['type'];
                //$enrollment->student_id = $request['student_id'];
                $enrollment->user_id = Auth::user()->id;

                if (isset($request['recalcular'])) {
                    $installments = Installment::where('enrollment_id', $enrollment->id)->where('state', null)->get();

                    $monto_pagado = 0.00;
                    $costo_matricula = 0.00;
                    $costo_periodo = (float) $request['period_cost'];
                    $costo_cuota = $costo_periodo;
                    $cuotas = 1;

                    foreach ($installments as $cuota) {
                        $cuota->state = date("Y-m-d H:i:s");

                        $pagos_temp = $cuota->payments;
                        if ($pagos_temp)
                            foreach ($pagos_temp as $pago)
                                $monto_pagado += $pago->amount;

                        $devoluciones_temp = $cuota->note_payments;
                        if ($devoluciones_temp)
                            foreach ($devoluciones_temp as $devolucion)
                                $monto_pagado -= $devolucion->amount;

                        $cuota->save();
                    }

                    /******** Creando cuotas ********/
                    if ($request['payment_type'] == 'credit') {
                        $costo_matricula = (float) $request['enrollment_cost'];

                        $temp_installment = new Installment();
                        $temp_installment->enrollment_id = $enrollment->id;
                        $temp_installment->order = 0;
                        $temp_installment->type = 'enrollment';
                        $temp_installment->amount = $costo_matricula;
                        $temp_installment->save();

                        $new_installments[] =   $temp_installment;

                        $cuotas = (int) $request['fees_quantity'];
                        $costo_cuota  = $costo_periodo / $cuotas;

                        for ($i = 0; $i < $cuotas; $i++) {
                            $temp_installment = new Installment();
                            $temp_installment->enrollment_id = $enrollment->id;
                            $temp_installment->order = ($i + 1);
                            $temp_installment->type = 'installment';
                            $temp_installment->amount = $costo_cuota;
                            $temp_installment->save();

                            $new_installments[] =   $temp_installment;
                        }
                    } else {
                        $temp_installment = new Installment();
                        $temp_installment->enrollment_id = $enrollment->id;
                        $temp_installment->order = 0;
                        $temp_installment->type = 'enrollment';
                        $temp_installment->amount = 0.00;
                        $temp_installment->save();

                        $new_installments[] =   $temp_installment;

                        $temp_installment = new Installment();
                        $temp_installment->enrollment_id = $enrollment->id;
                        $temp_installment->order = 1;
                        $temp_installment->type = 'installment';
                        $temp_installment->amount = $costo_periodo;
                        $temp_installment->save();

                        $new_installments[] =   $temp_installment;
                    }
                    $enrollment->payment_type = $request['payment_type'];
                    $enrollment->fees_quantity = $cuotas;
                    $enrollment->period_cost = $costo_periodo;

                    //Realizando Pagos
                    if ($monto_pagado > 0) {
                        /********************************* LOGICA:PAGOS *********************************/

                        // pagando matricula
                        if ($costo_matricula > 0) {

                            $vuelto = $monto_pagado - $costo_matricula;

                            $objPago = new stdClass();
                            $objPago->type = 'ticket';
                            //$objPago->payment_id=0;
                            $objPago->installment_id = $new_installments[0]->id;

                            if ($vuelto >= 0) {
                                $objPago->concept_type = "whole";
                                $objPago->amount = $costo_matricula;
                            } else {
                                $objPago->concept_type = "partial";
                                $objPago->amount = $monto_pagado;
                            }
                            $pago = $this->paymentRepository->to_pay_installment($objPago);
                            if (!$pago["success"])
                                throw new Error("Error al pagar la matricula");

                            $monto_pagado -= $costo_matricula;
                        }
                        // Pagando costo del ciclo
                        for ($i = 1; $i < $cuotas + 1; $i++) {
                            if ($monto_pagado <= 0) break;

                            $vuelto = $monto_pagado - $new_installments[$i]->amount;

                            $objPago = new stdClass();
                            $objPago->type = 'ticket';
                            //$objPago->payment_id=0;
                            $objPago->installment_id = $new_installments[$i]->id;

                            if ($vuelto >= 0) {
                                $objPago->concept_type = "whole";
                                $objPago->amount = $new_installments[$i]->amount;
                            } else {
                                $objPago->concept_type = "partial";
                                $objPago->amount = $monto_pagado;
                            }
                            $pago = $this->paymentRepository->to_pay_installment($objPago);
                            if (!$pago["success"])
                                throw new Error("Error al pagar la matricula");
                            $monto_pagado -= $new_installments[$i]->amount;
                        }


                        //Si pago mas de el costo total, devolver una nota
                        if ($monto_pagado > 0) {

                            $objPago = new stdClass();
                            $objPago->type = 'ticket';
                            //$objPago->payment_id=0;
                            $objPago->installment_id = $new_installments[$cuotas]->id;

                            $objPago->concept_type = "partial";
                            $objPago->amount = $monto_pagado;

                            $pago = $this->paymentRepository->to_pay_installment($objPago);
                            if ($pago["success"])
                                $ultimo_pago_id =  $pago["payment_id"];
                            else
                                throw new Error("Error al pagar la matricula");

                            //Creando nota de devolucion
                            $objPago = new stdClass();
                            $objPago->type = 'note';
                            $objPago->payment_id = $ultimo_pago_id;
                            $objPago->installment_id = $new_installments[$cuotas]->id;

                            $objPago->concept_type = "partial";
                            $objPago->amount = $monto_pagado;

                            $pago = $this->paymentRepository->to_pay_installment($objPago);
                            if (!$pago["success"])
                                throw new Error("Error crear nota de pago(devolucaion)");
                        }
                        /********************************* LOGICA:PAGOS *********************************/
                    }
                    $enrollment->save();
                    DB::commit();
                }
                return $enrollment;
            } else
                throw new NotFoundHttpException("No se ha encontrado la matricula");
        } catch (Exception $e) {
            DB::rollback();
            Log::debug($e);
            return null;
        }
    }
    public function updateAlumno($id, $alumnoUpdate)
    {
        $enrollment = Enrollment::find($id);
        DB::beginTransaction();
        try {
            if ($enrollment) {

                $student  = $enrollment->student;
                $student->school_id = $alumnoUpdate->student_ie_id;
                $student->graduation_year = $alumnoUpdate->student_graduation_year;

                // Agregar photo del alumno
                // code...

                $document_number = $alumnoUpdate->student_document_number;
                $entity = $student->entity;

                if ($entity->document_number != $document_number) {
                    $entity_temp = Entity::where("document_number", $document_number)->first();
                    if ($entity_temp) {
                        $student->entity_id = $entity_temp->id;
                        $entity = $entity_temp;
                    }
                }

                /** consumir api de reniec */
                // code...

                $entity->father_lastname = $alumnoUpdate->student_father_lastname;
                $entity->mother_lastname = $alumnoUpdate->student_mother_lastname;
                $entity->name = $alumnoUpdate->student_name;

                $entity->address = $alumnoUpdate->student_address;
                $entity->district_id = $alumnoUpdate->student_district_id;
                $entity->telephone = $alumnoUpdate->student_telephone;
                $entity->document_number = $document_number;
                $entity->birth_date = DateTime::createFromFormat('d/m/Y', $alumnoUpdate->student_birth_date)->format('Y-m-d');

                $entity->save();
                $student->save();
                $enrollment->save();
                DB::commit();
                //return [ "entity"=>$entity, "student"=>$student, "enrollment"=>$enrollment, ];
                return ["success" => true];
            } else
                throw new NotFoundHttpException("No se encuentra la matricula");
        } catch (Error $e) {
            Log::debug($e);
            DB::rollback();
            return ["success" => false];
        }
    }
    public function updateApoderado($id, $apoderadoUpdate)
    {

        $enrollment = Enrollment::find($id);
        DB::beginTransaction();
        try {
            if ($enrollment) {

                $relative = $enrollment->relative;
                $relative->occupation = $apoderadoUpdate->relative_occupation;
                $enrollment->relative_relationship = $apoderadoUpdate->relative_relationship;


                $entity = $relative->entity;

                $document_number = $apoderadoUpdate->relative_document_number;

                if ($entity->document_number != $document_number) {
                    $entity_temp = Entity::where("document_number", $document_number)->first();
                    if ($entity_temp) {
                        $relative->entity_id = $entity_temp->id;
                        $entity = $entity_temp;
                    }
                }

                /** consumir api de reniec */
                // code...

                $entity->father_lastname = $apoderadoUpdate->relative_father_lastname;
                $entity->mother_lastname = $apoderadoUpdate->relative_mother_lastname;
                $entity->name = $apoderadoUpdate->relative_name;

                $entity->address = $apoderadoUpdate->relative_address;
                $entity->telephone = $apoderadoUpdate->relative_telephone;
                $entity->document_number = $document_number;
                $entity->birth_date = DateTime::createFromFormat('d/m/Y', $apoderadoUpdate->relative_birth_date)->format('Y-m-d');

                $entity->save();
                $relative->save();
                $enrollment->save();
                DB::commit();
                //return [ "entity"=>$entity, "student"=>$student, "enrollment"=>$enrollment, ];
                return ["success" => true];

                /*
        relative_id: 1
    *   relative_father_lastname: apoder pat
    *   relative_mother_lastname: apoderado natt
    *   relative_name: apode nombre
    *   relative_birth_date: 18/02/2022
    *   relative_telephone: 1231231
    *   relative_occupation: presidente
    *   relative_address: asdasd
    *   relative_relationship: father
    *   relative_document_number: 00000001
        */
            } else
                throw new NotFoundHttpException("No se encuentra la matricula");
        } catch (Error $e) {
            Log::debug($e);
            DB::rollback();
            return ["success" => false];
        }
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

    public function cancel($id)
    {
        $enrollment = Enrollment::find($id);
        $status = 'err';
        if ($enrollment) {
            $enrollment->cancelled = 1;
            $isSaved = $enrollment->save();
            if ($isSaved) $status = 'ok';
        }
        return [
            'enrollment' => $enrollment,
            'status' => $status
        ];
    }

    public function getDataEnrollemnt($id)
    {
        $enrollment = Enrollment::with(['student.school.district', 'student.entity.district', 'relative.entity', 'classroom.level.level_type', 'classroom.level.period', 'user.entity', 'installments.payments', 'career'])->whereId($id)->firstOrFail();
        //return ["Self",$Self]
        return $enrollment;
    }

    // Busquedas
    public function search_enrollment($param)
    {
        $enrollment = Enrollment::with(['student.entity', 'classroom.level.level_type', 'classroom.level.period'])
            ->whereHas("student.entity", function ($q) use ($param) {
                $q->where("name", 'like', '%' . $param . '%')->orWhere(DB::raw("UPPER(CONCAT(name, ' ', father_lastname, ' ' ,mother_lastname))"), 'like', '%' . strtoupper($param) . '%')->orWhere("document_number", $param);
            })->get();
        return $enrollment;
    }
}
/*
    public function update(Request $request)
    {

        // dd($request->student_document_number);
        DB::beginTransaction();

        try {
            $enrollment = Enrollment::find($request->id);

            $enrollment->type = $request->type;
            $enrollment->classroom_id = $request->classroom_id;
            $enrollment->relative_relationship = $request->relative_relationship;
            $enrollment->career = $request->career;
            $enrollment->observations = $request->observations;

            if ($request->student_id == 0) {
                $entity = Entity::where('document_number', $request->student_document_number)->first();

                if ($entity == null) {

                    $entity = new Entity();
                    $entity->document_type = 'dni';
                    $entity->document_number = $request->student_document_number;

                    $entity->father_lastname = $request->student_father_lastname;
                    $entity->mother_lastname = $request->student_mother_lastname;
                    $entity->name = $request->student_name;

                    // $entity->birth_date=$request->student_birth_date;
                    $entity->birth_date = Carbon::createFromFormat('d/m/Y', $request->student_birth_date)->toDateString();
                    $entity->telephone = $request->student_telephone;


                    $entity->address = $request->student_address;
                    $entity->district_id = $request->student_district_id;

                    $entity->save();
                }

                $student = new Student();
                $student->entity_id = $entity->id;
                $student->school_id = $request->student_ie_id;
                $student->graduation_year = $request->student_graduation_year;

                if ($request->hasFile('student_photo_file')) {
                    $file = $request->file('student_photo_file');
                    if ($file->isValid()) {
                        $file_name = time() . '_' . $this->generate_random() . '.' . $file->getClientOriginalExtension();
                        $file->move('uploads/photo_files', $file_name);


                        $student->photo_file = $file_name;
                    }
                }

                $student->save();

                $enrollment->student_id = $student->id;
            } else {
                $enrollment->student_id = $request->student_id;
            }


            if ($request->relative_id == 0) {
                $entity = Entity::where('document_number', $request->relative_document_number)->first();

                if ($entity == null) {

                    $entity = new Entity();
                    $entity->document_type = 'dni';
                    $entity->document_number = $request->relative_document_number;

                    $entity->father_lastname = $request->relative_father_lastname;
                    $entity->mother_lastname = $request->relative_mother_lastname;
                    $entity->name = $request->relative_name;

                    // $entity->birth_date=$request->relative_birth_date;
                    $entity->birth_date = Carbon::createFromFormat('d/m/Y', $request->relative_birth_date)->toDateString();
                    $entity->telephone = $request->relative_telephone;

                    $entity->address = $request->relative_address;

                    $entity->save();
                }

                $relative = new Relative();
                $relative->entity_id = $entity->id;
                $relative->occupation = $request->relative_occupation;
                $relative->save();
                $enrollment->relative_id = $relative->id;
            } else {
                $enrollment->relative_id = $request->relative_id;
            }

            $enrollment->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
        return redirect()->action('EnrollmentController@main');
    }
*/
