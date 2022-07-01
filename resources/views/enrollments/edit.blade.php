@extends('templates.inspinia_template')


@section('title','Editar Matricula')

@section('styles-inter')
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/footable/footable.core.css') }}"
    rel="stylesheet">
@endsection

@section('styles')

{{-- <link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}"
rel="stylesheet"> --}}
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/datapicker/datepicker3.css') }}"
    rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/sweetalert/sweetalert.css') }}"
    rel="stylesheet">
{{-- <link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/clockpicker/clockpicker.css') }}"
rel="stylesheet"> --}}
{{-- <link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/iCheck/custom.css') }}"
rel="stylesheet"> --}}
{{-- <link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/footable/footable.core.css') }}"
rel="stylesheet"> --}}
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/jQueryUI/jquery-ui.css') }}"
    rel="stylesheet">

<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/jasny/jasny-bootstrap.min.css') }}"
    rel="stylesheet">







<style>
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    .ui-menu-item-wrapper {
        cursor: pointer;
    }

    ul.ui-autocomplete {
        z-index: 2200;
    }
</style>
@endsection


@section('heading')

<div class="row wrapper border-bottom white-bg page-heading" style="position: relative">
    <div class="col-lg-12" style="display: flex">
        <div style="flex-grow: 1">
            <h2>Información de Matricula</h2>
        </div>
        <div style="padding-top: 1.6rem">
            @php
                $status=$enrollment->status_descripion();
            @endphp
            <span
                class="badge {{ $status=='Vigente'?'badge-primary':($status=='Retirado'?'badge-danger':'') }}"
                style="font-size:16px">{{ $status }}</span>
            &nbsp;
            &nbsp;
            @if($status=='Vigente')
                <a href="javascript::void(0)" onclick="cancel_enrollment()"
                    style="color: #9d2222 !important;font-size: 1.2em">
                    <i class="fa fa-times"></i> Retirar Alumno
                </a>
            @endif
            @if($status=='Retirado')
                <a href="javascript::void(0)" onclick="reactive_enrollment()"
                    style="!important;font-size: 1.2em; color: #1AB394 !important;">
                    <i class="fas fa-check-double "></i>
                    Matricular
                </a>
            @endif
        </div>
    </div>
</div>
<div class="text-right">
    <a href="{{ action('ReportController@fichaMatricula', ['id' =>$enrollment->id ]) }}"
        class="btn btn-primary btn-sm" target="_blank" style="font-size:16px; border: none; color: #fff !important;">
        <i class="fas fa-eye"></i>
        Ver ficha
    </a>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12" id="app">
            <input type="hidden" id="id" name="id" value="{{ $enrollment->id }}">
            <div class="ibox animated bounceInDown">
                <!-------------------------------- Begin: Datos de la Matricula -------------------------------->
                <form  class="ibox-content spiner-3" id="form_matricula" v-on:submit="actualizarMatricula($event)">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <h3>Datos de la Matricula - CODIGO: {{ $enrollment->code }}</h3>
                    <div class="ibox-tools">
                        <template v-if="editMatricula">
                            <input type="submit" class="btn btn-sm badge-primary " style="font-size:14px; border: none"
                                value="Actualizar">
                            <input type="button" v-on:click="toggelModeEdition('matricula',false,true)"
                                class="btn btn-sm badge-secondary " style="font-size:14px; border: none;"
                                value="Cancelar">
                        </template>
                        <template v-else>
                            <input type="button" v-on:click="toggelModeEdition('matricula',true)"
                                class="btn btn-sm badge-primary " style="font-size:14px; border: none" value="Editar">
                        </template>
                    </div>
                    <div class="sk-spinner sk-spinner-rotating-plane"></div>
                    <div class="row">
                        <div class="form-group col-sm-2">
                            <label class="control-label">Tipo </label>
                            <select id="type" name="type" class="form-control" :disabled="!editMatricula" value="{{ $enrollment->type}}">
                                <option >Seleccionar tipo...</option>
                                <option value="normal" >Normal</option>
                                <option value="beca">Beca</option>
                                <option value="semi-beca">Semi-Beca</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="control-label">Ciclo/Nivel/Aula</label>
                            <div class="input-group">
                                <input id="classroom" type="text" name="classroom" autocomplete="no" placeholder=""
                                    class="form-control" required disabled
                                    value="{{ $enrollment->classroom->level->period->name.' - '.$enrollment->classroom->level->level_type->description.' - '.$enrollment->classroom->name }}">
                                <input type="hidden" id="hidden_classroom_id" name="classroom_id"
                                    value="{{ $enrollment->classroom->id }}">
                                <span v-show="editMatricula" class="input-group-btn">
                                    <a id="s-button" style="color: white; "
                                        onclick="clean_classroom_info('#classroom','#hidden_classroom_id')"
                                        title="Seleccionar Aula" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                            <p id="classroom-info"> <a href="javascript:void(0)"></a> </p>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Programa (Carrera Profesional)</label>
                            <div class="input-group" style="display: flex">
                                <input type="text" class="form-control" id="career" name="career" required
                                    value="{{ $enrollment->career->career }}" disabled>
                                <input type="hidden" id="career_id" name="career_id" class="form-control student"
                                    value="{{ $enrollment->career_id }}" required>
                                <span v-show="editMatricula" class="input-group-btn">
                                    <a id="clean-button-career" onclick="clean('#career','#career_id')"
                                        style="color:white;" title="Limpiar" class="btn btn-primary"><i
                                            class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <!-------------------------------- BEGIN:vista edicion -------------------------------->
                    <div v-if="editMatricula" id="container-editar-costo">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="control-label">Tipo de Pago</label> <br>
                                <div class="radio radio-success" style="display: inline">
                                    <input id="payment_type_cash" type="radio" value="cash" :disabled="!editMatricula"
                                        name="payment_type" v-on:change="radioTipoPago($event)"
                                        {{ $enrollment->payment_type=="cash"? "checked": "" }}>
                                    <label for="payment_type_cash"> Contado </label>
                                </div>

                                <div class="radio radio-primary" style="display: inline">
                                    <input id="payment_type_credit" type="radio" value="credit"
                                        :disabled="!editMatricula" name="payment_type" v-on:change="radioTipoPago($event)"
                                        {{ $enrollment->payment_type=="credit"? "checked": "" }}
                                        >
                                    <label for="payment_type_credit"> Cuotas </label>
                                </div>

                                <input type="hidden" id="hidden_payment_type_value" name="payment_type_value"
                                    value="{{ $enrollment->payment_type }}" :disabled="!editMatricula">
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="control-label">Costo de Matricula (S/)</label>
                                <input type="number" min="0" step="0.01" class="form-control" id="enrollment_cost"
                                    name="enrollment_cost" value="{{ $enrollment->installments->where("type", 'enrollment')->first()->amount}}"  :disabled="!editMatricula">
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="control-label">Costo del Ciclo (S/)</label>
                                <input type="number" min="0" step="0.01" class="form-control" id="period_cost"
                                    name="period_cost" value="{{ $enrollment->period_cost }}" required
                                    :disabled="!editMatricula">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <div class="form-group col-sm-4" id="fees_quantity_div" style="display:none" >
                                <label class="control-label">Numero de Cuotas</label>
                                <input type="number" min="1" step="1" class="form-control" id="fees_quantity"
                                    name="fees_quantity" value="{{ $enrollment->fees_quantity }}" required
                                    >
                            </div>
                            <div class="form-group col-sm-4" id="fee_cost_div" style="display:none">
                                <label class="control-label">Monto por Cuota (S/)</label>
                                <input type="number" min="0" step="0.01" class="form-control" id="fee_cost"
                                    value="{{ $enrollment->fees_quantity? $enrollment->period_cost/$enrollment->fees_quantity : "" }}"
                                    readonly >
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="control-label">Observaciones</label>
                                <textarea id="observations" name="observations" class="form-control" rows="3"
                                    >{{ $enrollment->observations }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-------------------------------- END:vista edicion -------------------------------->

                    <!-------------------------------- BEGIN:vista resumen -------------------------------->
                    <div v-else class="row" id="container-costo-guardado">
                        <div class="form-group col-sm-2">
                            <label class="control-label">Costo del Ciclo</label> <br><br>
                            <p id="enrollment_cost_saved">S/ @{{ enrollment?.period_cost }} </p>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">Tipo de Pago</label> <br><br>
                            <template v-if="enrollment?.payment_type=='cash' ">
                                <p id="description_pago_summary" style="font-weight: bolder;font-size: 1.3em;color: #5DB85D">
                                    Contado
                                </p>
                            </template>
                            <template v-else>
                                <p id="description_pago_summary" style="font-weight: bolder;font-size: 1.3em;color: #347AB7">
                                    @{{ enrollment?.fees_quantity }} Cuotas
                                </p>
                            </template>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">Usuario</label> <br><br>
                            <p> @{{ enrollment?.user.entity.name }} @{{ enrollment?.user.entity.father_lastname }}</p>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="control-label">Observaciones</label>
                            <textarea class="form-control" id="observations_saved" rows="3" disabled> @{{ enrollment?.observations }}</textarea>
                        </div>
                    </div>
                    <!-------------------------------- END:vista resumen -------------------------------->

                </form>
                <!-------------------------------- End: Costo del ciclo -------------------------------->
            </div>

            <div class="hr-line-dashed"></div>

            <div class="ibox animated bounceInDown" id="installments_div">
                <div class="ibox-content spiner-3" id="installments_control_container"></div>
            </div>

            <div class="ibox animated bounceInDown">
                <!-------------------------------- Begin: Datos del alumno -------------------------------->
                <form class="ibox-content spiner-3" id="form_alumno" v-on:submit="actualizarAlumno($event)">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <h3>Datos del Alumno</h3>
                    <div class="ibox-tools">
                        <template v-if="editAlumno">
                            <input type="submit" class="btn btn-sm badge-primary " style="font-size:14px; border: none"
                                value="Actualizar">
                            <input type="button" v-on:click="toggelModeEdition('alumno',false, true)"
                                class="btn btn-sm badge-secondary " style="font-size:14px; border: none;"
                                value="Cancelar">
                        </template>
                        <template v-else>
                            <input type="button" v-on:click="toggelModeEdition('alumno',true)"
                                class="btn btn-sm badge-primary " style="font-size:14px; border: none" value="Editar">
                        </template>
                    </div>
                    <div class="sk-spinner sk-spinner-rotating-plane"></div>
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="control-label">DNI</label>
                            <div class="input-group">
                                <input id="student_document_number" type="text" minlength="8" maxlength="8"
                                    pattern="\d{8}" name="student_document_number" autocomplete="no"
                                    placeholder="DNI ( 8 Dígitos )" class="form-control student"
                                    value="{{ $enrollment->student->entity->document_number }}" disabled required>
                                <input type="hidden" id="hidden_student_id" name="student_id"
                                    value="{{ $enrollment->student->id }}">
                                <span v-show="editAlumno" class="input-group-btn">
                                    {{-- <a id="s-button" style="color: white;" onclick="searchDoc()"  title="Buscar DNI" class="btn btn-primary"><i class="fas fa-search"></i></a> --}}
                                    <a id="s-button" style="color: white;;"
                                        onclick="clean('#student_document_number','#hidden_student_id')"
                                        title="Limpiar DNI" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Ap. Paterno</label>
                            <input type="text" class="form-control student" id="student_father_lastname"
                                name="student_father_lastname"
                                value="{{ $enrollment->student->entity->father_lastname }}"
                                :disabled="!editAlumno" required>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Ap. Materno</label>
                            <input type="text" class="form-control student" id="student_mother_lastname"
                                name="student_mother_lastname"
                                value="{{ $enrollment->student->entity->mother_lastname }}"
                                :disabled="!editAlumno" required>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="control-label">Nombres</label>
                            <input type="text" class="form-control student" id="student_name" name="student_name"
                                value="{{ $enrollment->student->entity->name }}" :disabled="!editAlumno" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Fecha de nacimiento</label>
                            <div class="input-group date" id="datepicker">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control student" autocomplete="no"
                                    placeholder="Ingresar Fecha" id="student_birth_date" name="student_birth_date"
                                    value="{{ date('d/m/Y') }}" required :disabled="!editAlumno">
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Telefono</label>
                            <input type="text" id="student_telephone" name="student_telephone" required
                                autocomplete="no" class="form-control student"
                                value="{{ $enrollment->student->entity->telephone }}" :disabled="!editAlumno">
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="form-group col-sm-8">
                            <label class="control-label">Dirección</label>
                            <textarea id="student_address" name="student_address" class="form-control student" rows="3"
                                value="{{ $enrollment->student->entity->address }}" :disabled="!editAlumno"
                                required></textarea>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Distrito</label>
                            <div class="input-group">
                                <input type="text" class="form-control student" id="student_district"
                                    name="student_district"
                                    value="{{ $enrollment->student->entity->district->name }}" disabled
                                    required>
                                <input type="hidden" id="hidden_student_district_id" name="student_district_id"
                                    value="{{ $enrollment->student->entity->district_id }}">
                                <span v-show="editAlumno" id="redo-student-district" class="input-group-btn">
                                    <a id="clean-button"
                                        onclick="clean('#student_district','#hidden_student_district_id')"
                                        style="color:white" title="Limpiar" class="btn btn-primary"><i
                                            class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-------------------------------- End: Datos del alumno -------------------------------->
                    <div class="hr-line-dashed"></div>
                    <!-------------------------------- Begin: IE Procedencia -------------------------------->
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="control-label">I.E. de Procedencia <span v-show="editAlumno" id="add_ie"><a
                                        href="javascript:void(0)" onclick="show_add_school_modal()"> <i
                                            class="fa fa-plus"></i> </a></span> </label>
                            <div class="input-group">
                                <input type="text" id="student_ie" name="student_ie" class="form-control student"
                                    value="{{ $enrollment->student->school->name.' - '.$enrollment->student->school->district->name }}"
                                    disabled required>
                                <input type="hidden" id="hidden_student_ie_id" name="student_ie_id"
                                    value="{{ $enrollment->student->school_id }}">
                                <span v-show="editAlumno" id="redo-student-ie" class="input-group-btn">
                                    <a id="clean-button" onclick="clean('#student_ie','#hidden_student_ie_id')"
                                        style="color:white" title="Limpiar" class="btn btn-primary"><i
                                            class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Año de egreso</label>
                            <div class="input-group date" id="datepicker-year">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control student" autocomplete="no"
                                    id="student_graduation_year" name="student_graduation_year"
                                    value="{{ date('Y') }}" required :disabled="!editAlumno">
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Foto</label><br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span v-show="editAlumno" class="btn btn-default btn-file"><span
                                        class="fileinput-new">Seleccionar Foto</span>
                                    <span v-show="editAlumno" class="fileinput-exists">Cambiar</span>
                                    <input id="student_photo_file" name="student_photo_file" type="file"
                                        onchange="previewFile()" disabled />
                                </span>
                                <span class="fileinput-filename"></span>
                                <a id="file_data_dismiss" href="#" class="close fileinput-exists"
                                    data-dismiss="fileinput" style="float: none">×</a>
                            </div>
                            <br>
                            <img style="display:display" id="photo_file"
                                src="{{ asset('uploads/photo_files/'.$enrollment->student->photo_file) }}"
                                height="200" width="150">
                        </div>
                    </div>
                </form>
                <!-------------------------------- End: Datos Alumno -------------------------------->
            </div>

            <!-------------------------------- Begin: Datos Apoderado -------------------------------->
            <div class="ibox animated bounceInDown">
                <form class="ibox-content spiner-3" id="form_apoderado" v-on:submit="actualizarApoderado($event)">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <h3>Datos del Apoderado</h3>
                    <div class="ibox-tools">
                        <template v-if="editApoderado">
                            <input type="submit" class="btn btn-sm badge-primary " style="font-size:14px; border: none"
                                value="Actualizar">
                            <input type="button" v-on:click="toggelModeEdition('apoderado',false, true)"
                                class="btn btn-sm badge-secondary " style="font-size:14px; border: none;"
                                value="Cancelar">
                        </template>
                        <template v-else>
                            <input type="button" v-on:click="toggelModeEdition('apoderado',true)"
                                class="btn btn-sm badge-primary " style="font-size:14px; border: none" value="Editar">
                        </template>
                    </div>
                    <div class="sk-spinner sk-spinner-rotating-plane"></div>

                    <div class="row">

                        <div class="form-group col-sm-4">
                            <label class="control-label">DNI</label>

                            <div class="input-group">
                                <input id="relative_document_number" type="text" minlength="8" maxlength="8"
                                    pattern="\d{8}" name="relative_document_number" autocomplete="no"
                                    placeholder="DNI ( 8 Dígitos )" class="form-control relative"
                                    value="{{ $enrollment->relative->entity->document_number }}" disabled
                                    required>
                                <input type="hidden" id="hidden_relative_id" name="relative_id"
                                    value="{{ $enrollment->relative->id }}">
                                <span v-show="editApoderado" class="input-group-btn">
                                    {{-- <a id="s-button" style="color: white;" onclick="searchDoc()"  title="Buscar DNI" class="btn btn-primary"><i class="fas fa-search"></i></a> --}}
                                    <!-- <a id="s-button" style="color: white;{{ $status=='Concluida'||$status=='Retirado'?'display:none':'' }}" onclick="clean_relative_info('.relative','#hidden_relative_id')"  title="Buscar DNI" class="btn btn-primary"><i class="fas fa-redo"></i></a> --->
                                    <a id="s-button" style="color: white;"
                                        onclick="clean('#relative_document_number', '#hidden_relative_id')"
                                        title="Buscar DNI" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Ap. Paterno</label>
                            <input type="text" class="form-control relative" id="relative_father_lastname"
                                name="relative_father_lastname"
                                value="{{ $enrollment->relative->entity->father_lastname }}"
                                :disabled="!editApoderado" required>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Ap. Materno</label>
                            <input type="text" class="form-control relative" id="relative_mother_lastname"
                                name="relative_mother_lastname"
                                value="{{ $enrollment->relative->entity->mother_lastname }}"
                                :disabled="!editApoderado" required>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="control-label">Nombres</label>
                            <input type="text" class="form-control relative" id="relative_name" name="relative_name"
                                value="{{ $enrollment->relative->entity->name }}" :disabled="!editApoderado"
                                required>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Fecha de nacimiento</label>
                            <div class="input-group date" id="datepicker-relative">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control relative" autocomplete="no"
                                    placeholder="Ingresar Fecha" id="relative_birth_date" name="relative_birth_date"
                                    value="{{ date('d/m/Y') }}" :disabled="!editApoderado"
                                    required>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Telefono</label>
                            <input type="text" id="relative_telephone" name="relative_telephone" required
                                autocomplete="no" class="form-control relative"
                                value="{{ $enrollment->relative->entity->telephone }}" :disabled="!editApoderado">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="control-label">Ocupación</label>
                            <input type="text" id="relative_occupation" name="relative_occupation"
                                :disabled="!editApoderado" autocomplete="no" class="form-control relative"
                                value="{{ $enrollment->relative->occupation }}" :disabled="!editApoderado">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="control-label">Dirección</label>
                            <textarea id="relative_address" name="relative_address" class="form-control relative"
                                rows="3" required
                                :disabled="!editApoderado">{{ $enrollment->relative->entity->address }}</textarea>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">Parentesco</label>
                            <!-- <select id="relative_relationship" name="relative_relationship" class="form-control relative" required {{ $status=='Concluida'||$status=='Retirado'?'disabled':'' }}> --->
                            <select id="relative_relationship" name="relative_relationship"
                                class="form-control relative" 
                                :disabled="!editApoderado">
                                <option value="">Seleccionar...</option>
                                <option value="father" {{ $enrollment->relative_relationship=="father"? "checked":"" }} >Padre</option>
                                <option value="mother" {{ $enrollment->relative_relationship=="mother"? "checked":"" }} >Madre</option>
                                <option value="brother" {{ $enrollment->relative_relationship=="brother"? "checked":"" }} >Hermano</option>
                                <option value="sister" {{ $enrollment->relative_relationship=="sister"? "checked":"" }} >Hermana</option>
                                <option value="uncle" {{ $enrollment->relative_relationship=="uncle"? "checked":"" }} >Tio(a)</option>
                                <option value="grandparent" {{ $enrollment->relative_relationship=="grandparent"? "checked":"" }} >Abuelo(a)</option>
                                <option value="cousin" {{ $enrollment->relative_relationship=="cousin"? "checked":"" }} >Primo(a)</option>
                                <option value="other" {{ $enrollment->relative_relationship=="other"? "checked":"" }} >Otro</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <input type="hidden" id="enrollment_ID" value="{{ $idEnrollment }}">
        <input id="baseUrl" name="base_url" type="hidden" value="{{ URL::to('/') }}">
    </div>
</div>

<div class="modal inmodal fade" id="add_payment_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content" id="add_payment_modal_body">

        </div>
    </div>
</div>

<div class="modal inmodal fade" id="history_payment_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="history_payment_modal_body">

        </div>
    </div>
</div>

<div class="modal inmodal fade" id="add_school_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <div class="modal-content" id="add_school_modal_body">

        </div>
    </div>
</div>

<iframe id="iFramePdf" name="iFramePdf" src="" style="display:none"></iframe>


@endsection

@section('scripts-inter')
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/footable/footable.all.min.js') }}">
</script>
@endsection

@section('scripts')

<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/jquery-ui/jquery-ui.min.js') }}">
</script>
<script
    src="{{ asset('inspinia_admin-v2.9.2/js/plugins/datapicker/bootstrap-datepicker.js') }}">
</script>
<script
    src="{{ asset('inspinia_admin-v2.9.2/js/plugins/datapicker/bootstrap-datepicker.es.min.js') }}">
</script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/sweetalert/sweetalert.min.js') }}">
</script>
{{-- <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/clockpicker/clockpicker.js') }}">
</script> --}}
{{-- <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/footable/footable.all.min.js') }}">
</script> --}}
{{-- <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/iCheck/icheck.min.js') }}">
</script> --}}

<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/jasny/jasny-bootstrap.min.js') }}">
</script>
<script src="{{ asset('js/enrollment/edit.js') }}"></script>


<script>
    var redo = false;


    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('menu-enrollments').classList.add("active");
        document.getElementById('submenu-enrollments-2').classList.add("active");
        // document.getElementById("student_document_number").focus();

        $('#relative_relationship option[value="{{ $enrollment->relative_relationship }}"]').prop('selected',
            true);
        $('#type option[value="{{ $enrollment->type }}"]').prop('selected', true);

    });



    $('#datepicker,#datepicker-relative').datepicker({
        format: 'dd/mm/yyyy',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        language: 'es',
    });


    $('#datepicker-year').datepicker({
        format: "yyyy",
        weekStart: 1,
        orientation: "bottom",
        keyboardNavigation: false,
        viewMode: "years",
        minViewMode: "years"
    });

    $("#datepicker-year").datepicker("setDate", "{{ $enrollment->student->graduation_year }}");
    $("#datepicker").datepicker("setDate",
        "{{ date_format(date_create($enrollment->student->entity->birth_date),'d/m/Y') }}"
    );
    $("#datepicker-relative").datepicker("setDate",
        "{{ date_format(date_create($enrollment->relative->entity->birth_date),'d/m/Y') }}"
    );


    $('#period_cost').keyup(function (e) {
        fee_cost();
    });


    $("#classroom").autocomplete({
        source: '{{ action("ClassroomController@search_classroom") }}',
        delay: 500,
        minLength: 1,
        select: function (event, ui) {
            $("#hidden_classroom_id").val(ui.item.id);

            $('#classroom').prop('disabled', true);

            $('#classroom-info > a').html('Vacantes disponibles: ' + Number(ui.item.classroom.vacancy - ui
                .item.classroom.enrollments_count));

            $('#period_cost').val(ui.item.classroom.level.price);

            fee_cost();
        }
    });


    $("#student_document_number").autocomplete({
        source: '{{ action("StudentController@search_student") }}',
        //delay: 500,
        minLength: 3,
        select: function (event, ui) {
            $("#hidden_student_id").val(ui.item.id);

            $('#student_father_lastname').val(ui.item.student.entity.father_lastname);
            $('#student_mother_lastname').val(ui.item.student.entity.mother_lastname);
            $('#student_name').val(ui.item.student.entity.name);


            var date = ui.item.student.entity.birth_date;

            var birth_date = date.split("-");
            // var birth_date= date.split("-").reverse().join("/");

            $('#datepicker').datepicker("setDate", new Date(birth_date[0], birth_date[1] - 1, birth_date[
                2]));

            // console.log(birth_date);

            $('#student_telephone').val(ui.item.student.entity.telephone);

            $('#student_address').val(ui.item.student.entity.address);
            $('#student_district').val(ui.item.student.entity.district.name);
            $('#hidden_student_district_id').val(ui.item.student.entity.district.id);

            $('#student_ie').val(ui.item.student.school.name + ' - ' + ui.item.student.school.district
                .name);

            // $('#student_graduation_year').val(ui.item.student.graduation_year);

            $('#datepicker-year').datepicker("setDate", new Date(ui.item.student.graduation_year, 0, 1));


            var base_url = $('#base_url').val();

            $('#file_data_dismiss').click();
            $('#photo_file').css('display', 'block');
            $('#photo_file').prop('src', base_url + '/uploads/photo_files/' + ui.item.student.photo_file);

            $('#redo-student-district').css('display', 'none');
            $('#redo-student-ie').css('display', 'none');

            $('#student_photo_file').prop('disabled', true);


            $('.student').prop('disabled', true);
            $('select.student').prop('disabled', true);


            $('#add_ie').css('display', 'none');

            // console.log(ui.item);
        }
    });

    $("#career").autocomplete({
        source: '{{ action("CareerController@search_career") }}',
        //delay: 500,
        minLength: 3,
        select: function (event, ui) {
            console.log(ui);
            $("#career_id").val(ui.item.id);
            $('#career').prop('readonly', true);
        }
    });


    $("#relative_document_number").autocomplete({
        source: '{{ action("RelativeController@search_relative") }}',
        //delay: 500,
        minLength: 3,
        select: function (event, ui) {
            $("#hidden_relative_id").val(ui.item.id);

            $('#relative_father_lastname').val(ui.item.relative.entity.father_lastname);
            $('#relative_mother_lastname').val(ui.item.relative.entity.mother_lastname);
            $('#relative_name').val(ui.item.relative.entity.name);


            var date = ui.item.relative.entity.birth_date;

            var birth_date = date.split("-");
            // var birth_date= date.split("-").reverse().join("/");

            $('#datepicker-relative').datepicker("setDate", new Date(birth_date[0], birth_date[1] - 1,
                birth_date[2]));

            // console.log(birth_date);

            $('#relative_telephone').val(ui.item.relative.entity.telephone);
            $('#relative_address').val(ui.item.relative.entity.address);

            $('.relative').prop('disabled', true);
            $('select.relative').prop('disabled', true);
            // console.log(ui.item);
        }
    });

    $("#student_district").autocomplete({
        source: '{{ action("AdministratorController@search_district") }}',
        //delay: 500,
        minLength: 3,
        select: function (event, ui) {
            $("#hidden_student_district_id").val(ui.item.id);

            $('#student_district').prop('disabled', true);

        }
    });



    $("#student_ie").autocomplete({
        source: '{{ action("SchoolController@search_ie") }}',
        //delay: 500,
        minLength: 3,
        select: function (event, ui) {
            $("#hidden_student_ie_id").val(ui.item.id);

            $('#student_ie').prop('disabled', true);

        }
    });


    $('input[name="payment_type"]').click(function () {
        // console.log($(this).val()) 
        

    });



    function searchDoc() {
        var doc = $('#student_document_number').val();

        if (doc != '') {
            if (!redo) {
                $('.spiner-3').toggleClass('sk-loading');
                $.get('{{ action("AdministratorController@quertium",["dni"=>""]) }}/' +
                    doc,
                    function (data) {

                        $('.spiner-3').toggleClass('sk-loading');

                        if (data.success) {
                            $('#student_document_number').val(data.person["dni"]).prop('disabled', true);
                            $('#student_father_lastname').val(data.person["apellidoPaterno"]).prop('disabled',
                                true);
                            $('#student_mother_lastname').val(data.person["apellidoMaterno"]).prop('disabled',
                                true);
                            $('#student_name').val(data.person["nombres"]).prop('disabled', true);



                            $('#s-button i').addClass('fa-redo').removeClass('fa-search');
                            $('#s-button').attr('title', 'Nueva busqueda');
                            redo = true;

                        } else {
                            $('#student_document_number').val('');
                            $('#student_document_number').focus();
                            swal("Error", 'No se ha encontrado el DNI especificado.', "error");
                        }
                    }, "json");

            } else {
                $('#student_document_number').val('').prop('disabled', false);
                $('#student_father_lastname').val('').prop('disabled', false);
                $('#student_mother_lastname').val('').prop('disabled', false);
                $('#student_name').val('').prop('disabled', false);

                $('#s-button i').removeClass('fa-redo').addClass('fa-search');
                $('#s-button').attr('title', 'Buscar DNI');
                redo = false;
                $('#student_document_number').focus();
            }

        } else {
            swal("Error", 'Debe ingresar un numero de DNI', "error");
        }

    }





    function load_installments_control_view(enrollment_id) {

        $('#installments_div').children('.ibox-content').toggleClass('sk-loading');
        $.ajax({
            type: "get",
            url: "{{ action('InstallmentController@installments_control_view',['enrollment_id'=>'']) }}/" +
                enrollment_id,
            success: function (data) {
                $('#installments_div').children('.ibox-content').toggleClass('sk-loading');
                $('#installments_div > .ibox-content').html(data);

            }
        });
    }

    function show_add_payment_modal(installment_id) {

        $.ajax({
            type: "get",
            url: "{{ action('PaymentController@create',['installment_id'=>'']) }}/" +
                installment_id,
            success: function (data) {
                setModal_addPayment(data);
            }
        });
    }


    function setModal_addPayment(data) {

        $('#add_payment_modal_body').html(data);

        $('#add_payment_modal').on('shown.bs.modal', function () {
            $('#payment_amount').focus();
        });

        $('#add_payment_modal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $('#payment_form').unbind();
        $('#payment_form').submit(function (e) {
            e.preventDefault();


            var url = $(this).attr('action');

            var installment_type = $('#installment_type').val();
            var installment_amount = parseFloat($('#installment_amount').val());
            var installment_balance = parseFloat($('#installment_balance').val());
            var payment_type = parseFloat($('#payment_type').val());

            // console.log(url);


            var payment_amount = parseFloat($(this).find('#payment_amount').val());

            if (installment_type == 'enrollment') {
                $('#concept_type').val('whole');
                var form_data = $(this).serialize();
                // console.log(installment_amount);
                // console.log(payment_amount);
                if (installment_amount == payment_amount) {
                    add_payment_ajax(url, form_data);
                } else {
                    alert('Debe pagar el monto exacto de la deuda.');
                }

            }

            if (installment_type == 'installment') {
                var new_balance = installment_balance + payment_amount;

                if (new_balance <= installment_amount) {
                    if (payment_amount == installment_amount) {
                        $('#concept_type').val('whole');
                    } else {
                        $('#concept_type').val('partial');
                    }
                    var form_data = $(this).serialize();
                    add_payment_ajax(url, form_data);
                } else {
                    alert('El abono a esta cuota excede el monto total.');
                }

            }


        });
    }

    function add_payment_ajax(url, form_data) {
        $("#button-abonar").prop("disabled", true);
        $("#button-abonar").html("Abonando...");
        $.ajax({
            type: "post",
            url: url,
            dataType: 'json',
            data: form_data,
            success: function (data) {
                console.log(data);
                var id = $('#id').val();
                $('#add_payment_modal').modal('hide');
                load_installments_control_view(id);

            }
        });
    }

    function show_history_payment_modal(installment_id, refresh) {

        $.ajax({
            type: "get",
            url: "{{ action('PaymentController@history',['installment_id'=>'']) }}/" +
                installment_id,
            success: function (data) {
                if (!refresh) {
                    setModal_historyPayment(data);
                } else {
                    $('#history_payment_modal_body').html(data);
                    $('.footable').unbind();
                    $('.footable').footable({
                        paginate: false,
                    });
                    $('.footable-sort-indicator').css('display', 'none');
                    $('th').unbind();
                }

            }
        });
    }

    function setModal_historyPayment(data) {


        $('#history_payment_modal_body').html(data);


        $('#history_payment_modal').unbind();
        $('#history_payment_modal').on('shown.bs.modal', function () {
            $('.footable').unbind();
            $('.footable').footable({
                paginate: false,
            });
            $('.footable-sort-indicator').css('display', 'none');
            $('th').unbind();
        });


        $('#history_payment_modal').on('hide.bs.modal', function () {
            var id = $('#id').val();
            load_installments_control_view(id);
        });

        $('#history_payment_modal').modal({
            backdrop: 'static',
            keyboard: false
        });





    }

    function revert_payment(installment_id, payment_id, amount) {

        arr = {
            '_token': "{{ csrf_token() }}",
            'installment_id': installment_id,
            'payment_id': payment_id,
            'type': "note",
            'concept_type': "none",
            'amount': amount
        };
        swal({
            title: "¿Ésta seguro de revertir este pago?",
            text: "Se añadirá una nota de crédito, y este pago sera revertido.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: 'Si, Revertir',
            closeOnConfirm: true
        }, function () {
            $.ajax({
                type: "post",
                dataType: 'json',
                data: arr,
                url: "{{ action('PaymentController@store') }}",
                success: function (data) {
                    console.log(data);
                    if (data.success) {
                        show_history_payment_modal(data.installment_id, true);
                    } else {
                        alert('error');
                    }

                }
            });
        });
    }



    function clean(selector_elem, selector_hidden) {
        $(selector_elem).val('');
        $(selector_hidden).val(0)
        $(selector_elem).prop('disabled', false);
        $(selector_elem).focus();
    }

    function clean_classroom_info(selector_elem, selector_hidden) {
        $(selector_elem).val('');
        $(selector_hidden).val(0)
        $(selector_elem).prop('disabled', false);
        $(selector_elem).focus();

        $('#classroom-info > a').html('');
        $('#period_cost').val('0.00');
        $('#fee_cost').val('0.00');

    }

    function clean_student_info(selector_elem, selector_hidden) {
        $(selector_elem).val('');
        $(selector_hidden).val(0)
        $(selector_elem).prop('disabled', false);
        $('#student_document_number').focus();

        // $('#student_licence_type_id').prop('disabled',false);
        $('#redo-student-district').css('display', 'block');
        $('#redo-student-ie').css('display', 'block');
        $('#student_photo_file').prop('disabled', false);
        $('#photo_file').prop('src', '');
        $('#photo_file').css('display', 'none');
        $('#add_ie').css('display', 'inline');
    }

    function clean_relative_info(selector_elem, selector_hidden) {
        $(selector_elem).val('');
        $(selector_hidden).val(0)
        $(selector_elem).prop('disabled', false);
        $('#relative_document_number').focus();


    }

    function fee_cost() {
        var period_cost = $('#period_cost').val();
        var fees_quantity = $('#fees_quantity').val();
        var fee_cost = 0.00;

        if (period_cost != '' && fees_quantity != 0) {
            fee_cost = Number(Number(period_cost) / Number(fees_quantity)).toFixed(2);
        }

        $('#fee_cost').val(fee_cost);
    }

    function previewFile() {
        $('#photo_file').css('display', 'block');

        var preview = document.querySelector('img');
        var file = document.querySelector('input[type=file]').files[0];


        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            $('#photo_file').css('display', 'none');
        }


    }

    function payment_document_pdf(id) {
        var myIframe = document.getElementById('iFramePdf');
        myIframe.addEventListener("load", function () {

            printTrigger("iFramePdf");
        });


        $("#formPaymentDocuments > #payment_id").val(id);

        $("#formPaymentDocuments").submit();
    }

    function printTrigger(elementId) {
        var getMyFrame = document.getElementById(elementId);
        getMyFrame.focus();
        getMyFrame.contentWindow.print();
    }

    function cancel_enrollment() {
        var id = $('#id').val();
        arr = {
            '_token': "{{ csrf_token() }}",
            'id': id
        };
        swal({
            title: "¿Ésta seguro de Retirar a este alumno?",
            text: "La matricula será cancelada y el alumno retirado.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: 'Si',
            closeOnConfirm: true
        }, function () {
            $.ajax({
                type: "post",
                dataType: 'json',
                data: arr,
                url: "{{ action('EnrollmentController@cancel') }}",
                success: function (data) {
                    console.log(data);
                    if (data.success) {
                        window.location.reload();
                    }
                }
            });
        });
    }

    function reactive_enrollment() {
        /*
        var id=$('#id').val();
        arr={'_token':"{{ csrf_token() }}",'id':id};
        swal({
            title: "¿Ésta seguro de Reactivar la matricula del alumno?",
            text: "La matricula será cancelada y el alumno retirado.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: 'Si',
            closeOnConfirm: true
        }, function () {
            $.ajax({
                type: "post",
                dataType:'json',       
                data: arr,
                url: "{{ action('EnrollmentController@cancel') }}",
                success: function(data){
                    console.log(data);
                    if(data.success){
                        window.location.reload();
                    }
                }                
            });
        });
       */
        alert("reactivando alumno");
    }

    // /-------------------

    function show_add_school_modal() {

        $.ajax({
            type: "get",
            url: "{{ action('SchoolController@create') }}",
            success: function (data) {
                setModal_addSchool(data);
            }
        });

    }

    function setModal_addSchool(data) {

        $('#add_school_modal_body').html(data);

        $('#add_school_modal').on('shown.bs.modal', function () {
            $('#school_name').focus();
        });

        $('#add_school_modal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#school_district").unbind();
        $("#school_district").autocomplete({
            source: '{{ action("AdministratorController@search_district") }}',
            //delay: 500,
            minLength: 3,
            select: function (event, ui) {
                $("#hidden_school_district_id").val(ui.item.id);

                $('#school_district').prop('disabled', true);

            }
        });



        $('#school_form').unbind();
        $('#school_form').submit(function (e) {
            e.preventDefault();


            var url = $(this).attr('action');
            var form_data = $(this).serialize();

            $.ajax({
                type: "post",
                url: url,
                dataType: 'json',
                data: form_data,
                success: function (data) {
                    console.log(data);
                    if (data.success) {
                        $('#add_school_modal').modal('hide');
                    } else {
                        alert('Error');
                    }


                }
            });

        });

    }
    
</script>


@endsection