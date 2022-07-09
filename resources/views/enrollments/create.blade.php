@extends('templates.inspinia_template')


@section('title','Nueva Matricula')

@section('styles')

<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
{{-- <link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/clockpicker/clockpicker.css') }}" rel="stylesheet"> --}}
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
{{-- <link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/footable/footable.core.css') }}" rel="stylesheet"> --}}
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/jQueryUI/jquery-ui.css') }}" rel="stylesheet">

<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">


<style>
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    .ui-menu-item-wrapper{
        cursor: pointer;
    }

    ul.ui-autocomplete {
        z-index: 2200;
    }

</style>
@endsection


@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2>Registrar Matricula</h2>
        </div>
    </div>
@endsection




@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-12">
        
        
        <form id="create-form" method="POST" class="form-horizontal" autocomplete="no" action="{{action('EnrollmentController@store')}}" enctype='multipart/form-data'>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <!--------------------------- BEGIN: Datos del Alumno --------------------------->
            <div class="ibox animated bounceInDown">                
                
                <div class="ibox-content spiner-3">
        
                    <h3>Datos del Alumno</h3>
            
                    <div class="sk-spinner sk-spinner-rotating-plane"></div>
                            
                    <div class="row">
                                            
                        <div class="form-group col-sm-4">
                            <label class="control-label">DNI</label>
                                
                            <div class="input-group">
                                <input id="student_document_number" type="text" minlength="8" maxlength="8" pattern="\d{8}" name="student_document_number" autocomplete="no" placeholder="DNI ( 8 Dígitos )" class="form-control student" required >
                                <input type="hidden" id="hidden_student_id" name="student_id" value="0">
                                <span class="input-group-btn">
                                    {{-- <a id="s-button" style="color: white;" onclick="searchDoc()"  title="Buscar DNI" class="btn btn-primary"><i class="fas fa-search"></i></a> --}}
                                    <a id="s-button" style="color: white;" onclick="clean_student_info('.student','#hidden_student_id')"  title="Buscar DNI" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>    
                                            
                        <div class="form-group col-sm-4">
                            <label class="control-label">Ap. Paterno</label>
                            <input type="text" class="form-control student" id="student_father_lastname" name="student_father_lastname" required>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Ap. Materno</label>
                            <input type="text" class="form-control student" id="student_mother_lastname" name="student_mother_lastname" required>
                        </div>
        
                                     
        
                    </div>
                
                    <div class="hr-line-dashed"></div>
        
        
                    <div class="row">
                                                
                        <div class="form-group col-sm-4">
                            <label class="control-label">Nombres</label>
                            <input type="text" class="form-control student" id="student_name" name="student_name" required>
                        </div>
                                           
    
                        <div class="form-group col-sm-4">
                            <label class="control-label">Fecha de nacimiento</label>
                            
                            <div class="input-group date" id="datepicker">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control student" autocomplete="no" placeholder="Ingresar Fecha" id="student_birth_date"  name="student_birth_date"  required>
                            </div>
                        </div> 
    
                        <div class="form-group col-sm-4">
                            <label class="control-label">Telefono</label>
                            

                            <input type="text" id="student_telephone" name="student_telephone" required autocomplete="no" class="form-control student">
                        </div>
            
                    </div>
    
                    <div class="hr-line-dashed"></div>
    
    
                    <div class="row">
                                            
                        <div class="form-group col-sm-8">
                            <label class="control-label">Dirección</label>
                            <textarea id="student_address" name="student_address" class="form-control student" rows="3" required></textarea>
                        </div>
                                                
                        <div class="form-group col-sm-4">
                            <label class="control-label">Distrito</label>
                            <div class="input-group">
                                <input type="text" class="form-control student" id="student_district" name="student_district" required>
                                <input type="hidden" id="hidden_student_district_id" name="student_district_id" value="0">
                                <span id="redo-student-district" class="input-group-btn">
                                    <a id="clean-button" onclick="clean('#student_district','#hidden_student_district_id')" style="color:white" title="Limpiar" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>
            
                    </div>

                    <div class="hr-line-dashed"></div>
    
    
                    <div class="row">
                                            
                        <div class="form-group col-sm-4">
                            <label class="control-label">I.E. de Procedencia</label> <p id="add_ie" style="display: inline"><a href="javascript:void(0)" onclick="show_add_school_modal()"> <i class="fa fa-plus"></i> </a></p>
                            
                            <div class="input-group">
                                <input type="text" id="student_ie" name="student_ie" class="form-control student" required>
                                <input type="hidden" id="hidden_student_ie_id" name="student_ie_id" value="0">
                                <span id="redo-student-ie" class="input-group-btn">
                                    <a id="clean-button" onclick="clean('#student_ie','#hidden_student_ie_id')" style="color:white" title="Limpiar" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>
                                                
                        {{-- <div class="form-group col-sm-4">
                            <label class="control-label">Ciudad de I.E.</label>
                            <input id="student_ie_city" name="student_ie_city" class="form-control student" required>
                        </div> --}}

                        <div class="form-group col-sm-4">
                            <label class="control-label">Año de egreso</label>
                            
                            <div class="input-group date" id="datepicker-year">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control student" autocomplete="no" id="student_graduation_year" name="student_graduation_year"  value="{{ date('Y')}}" required>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Foto</label><br>

                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar Foto</span>
                                <span class="fileinput-exists">Cambiar</span>
                                    <input id="student_photo_file" name="student_photo_file" type="file" onchange="previewFile()"/>
                                </span>
                                <span class="fileinput-filename"></span>
                                <a id="file_data_dismiss" href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                            </div> 

                            <br>
                            
                            <img style="display:none" id="photo_file" src="" height="200" width="150">
                            <input id="base_url" type="hidden" value="{{URL::to('/')}}">
                        </div>
            
                    </div>
                            
                </div>
            </div>
            <!--------------------------- END: Datos del Alumno --------------------------->


            <!--------------------------- BEGIN: Datos del Apoderado --------------------------->
            <div class="ibox animated bounceInDown">                
                
                <div class="ibox-content spiner-3">
        
                    <h3>Datos del Apoderado</h3>
            
                    <div class="sk-spinner sk-spinner-rotating-plane"></div>
                            
                    <div class="row">
                                            
                        <div class="form-group col-sm-4">
                            <label class="control-label">DNI</label>
                                
                            <div class="input-group">
                                <input id="relative_document_number" type="text" minlength="8" maxlength="8" pattern="\d{8}" name="relative_document_number" autocomplete="no" placeholder="DNI ( 8 Dígitos )" class="form-control relative" required >
                                <input type="hidden" id="hidden_relative_id" name="relative_id" value="0">
                                <span class="input-group-btn">
                                    {{-- <a id="s-button" style="color: white;" onclick="searchDoc()"  title="Buscar DNI" class="btn btn-primary"><i class="fas fa-search"></i></a> --}}
                                    <a id="s-button" style="color: white;" onclick="clean_relative_info('.relative','#hidden_relative_id')"  title="Buscar DNI" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div>    
                                            
                        <div class="form-group col-sm-4">
                            <label class="control-label">Ap. Paterno</label>
                            <input type="text" class="form-control relative" id="relative_father_lastname" name="relative_father_lastname" required>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Ap. Materno</label>
                            <input type="text" class="form-control relative" id="relative_mother_lastname" name="relative_mother_lastname" required>
                        </div>
        
                    </div>
                
                    <div class="hr-line-dashed"></div>
        
        
                    <div class="row">
                                                
                        <div class="form-group col-sm-4">
                            <label class="control-label">Nombres</label>
                            <input type="text" class="form-control relative" id="relative_name" name="relative_name" required>
                        </div>
                                           
    
                        <div class="form-group col-sm-4">
                            <label class="control-label">Fecha de nacimiento</label>
                            
                            <div class="input-group date" id="datepicker-relative">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control relative" autocomplete="no" placeholder="Ingresar Fecha" id="relative_birth_date"  name="relative_birth_date" value="{{ date('d/m/Y')}}" required>
                            </div>
                        </div> 
    
                        <div class="form-group col-sm-4">
                            <label class="control-label">Telefono</label>
                            
                            <input type="text" id="relative_telephone" name="relative_telephone" required autocomplete="no" class="form-control relative">
                        </div>
            
                    </div>
    
                    <div class="hr-line-dashed"></div>
    
    
                    <div class="row">

                        <div class="form-group col-sm-4">
                            <label class="control-label">Ocupación</label>
                            <input type="text" id="relative_occupation" name="relative_occupation" required autocomplete="no" class="form-control relative">
                        </div>
                                            
                        <div class="form-group col-sm-6">
                            <label class="control-label">Dirección</label>
                            <textarea id="relative_address" name="relative_address" class="form-control relative" rows="3" required></textarea>
                        </div>


                        <div class="form-group col-sm-2">
                            <label class="control-label">Parentesco</label>
                            <select id="relative_relationship" name="relative_relationship" class="form-control relative" required>
                                <option value="">Seleccionar...</option>
                                <option value="father">Padre</option>
                                <option value="mother">Madre</option>
                                <option value="brother">Hermano</option>
                                <option value="sister">Hermana</option>
                                <option value="uncle">Tio(a)</option>
                                <option value="grandparent">Abuelo(a)</option>
                                <option value="cousin">Primo(a)</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                                                
                    </div>

                </div>
            </div>
            <!--------------------------- END: Datos del Apoderado --------------------------->

            <!--------------------------- BEGIN: Datos de la Matricula --------------------------->
            <div class="ibox animated bounceInDown">                
                
                <div class="ibox-content spiner-3">
        
                    <h3>Datos de la Matricula</h3>
            
                    <div class="sk-spinner sk-spinner-rotating-plane"></div>
                            
                    <div class="row">
                                            
                        <div class="form-group col-sm-2">
                            <label class="control-label">Tipo</label>
                                
                            <select id="type" name="type" class="form-control" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="normal">Normal</option>
                                <option value="beca">Beca</option>
                                <option value="semi-beca">Semi-Beca</option>
                                
                                
                            </select>
                        </div>    
                                            
                        <div class="form-group col-sm-6">
                            <label class="control-label">Ciclo/Nivel/Aula</label>
                                
                            <div class="input-group">
                                <input id="classroom" type="text" name="classroom" autocomplete="no" placeholder="" class="form-control" required >
                                <input type="hidden" id="hidden_classroom_id" name="classroom_id" value="0">
                                <span class="input-group-btn">
                                    {{-- <a id="s-button" style="color: white;" onclick="searchDoc()"  title="Buscar DNI" class="btn btn-primary"><i class="fas fa-search"></i></a> --}}
                                    <a id="s-button" style="color: white;" onclick="clean_classroom_info('#classroom','#hidden_classroom_id')"  title="Seleccionar Aula" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                            <p id="classroom-info"> <a href="javascript:void(0)"></a> </p>
                        </div> 

                        <div class="form-group col-sm-4">
                            <label class="control-label">Programa (Carrera Profesional)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="career" name="career" required>
                                <input type="hidden" id="career_id" name="career_id" class="form-control student" value="-1" required>
                                <span id="redo-student-ie" class="input-group-btn">
                                    <a id="clean-button" onclick="clean('#career','#career_id')" style="color:white" title="Limpiar" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                                </span>
                            </div>
                        </div
                    </div>
                
                    <div class="hr-line-dashed"></div>
        
        
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="control-label">Tipo de Pago</label> <br>
                            <div class="radio radio-success" style="display: inline">
                                <input id="payment_type_cash" type="radio" value="cash" name="payment_type" checked="">
                                <label for="payment_type_cash"> Contado </label>
                            </div>

                            <div class="radio radio-primary" style="display: inline">
                                <input id="payment_type_credit" type="radio" value="credit" name="payment_type">
                                <label for="payment_type_credit"> Cuotas </label>
                            </div>
                            
                            <input type="hidden" id="hidden_payment_type_value" name="payment_type_value" value="cash">
                        </div>
    
                        <div class="form-group col-sm-4">
                            <label class="control-label">Costo de Matricula (S/)</label>
                            <input type="number" min="0" step="0.01" class="form-control" id="enrollment_cost" name="enrollment_cost" required value="0.00">
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label">Costo del Ciclo (S/)</label>
                            <input type="number" min="0" step="0.01" class="form-control" id="period_cost" name="period_cost" value="0.00" required>
                        </div>
    
                        
            
                    </div>
    
                    <div class="hr-line-dashed"></div>

                    
    
    
                    <div class="row">

                        <div class="form-group col-sm-4" id="fees_quantity_div" style="display:none">
                            <label class="control-label">Numero de Cuotas</label>
                            <input type="number" min="1" step="1" class="form-control" id="fees_quantity" name="fees_quantity" value="1" required>
                        </div>

                        <div class="form-group col-sm-4" id="fee_cost_div" style="display:none">
                            <label class="control-label">Monto por Cuota (S/)</label>
                            <input type="number" min="0" step="0.01" class="form-control" id="fee_cost" value="0.00" readonly>
                        </div>
                                            
                        <div class="form-group col-sm-4">
                            <label class="control-label">Observaciones</label>
                            <textarea id="observations" name="observations" class="form-control" rows="3" ></textarea>
                        </div>

                    </div>

    
                            
                </div>
            </div>
            <!--------------------------- END: Datos de la Matricula --------------------------->

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-9">
                    {{-- <a class="btn btn-white" href="">Listar clientes</a> --}}
                    <button id="submit" class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </div>

        </form>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="add_school_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <div class="modal-content" id="add_school_modal_body">
        </div>
    </div>
</div>


@endsection



@section('scripts')

<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/datapicker/bootstrap-datepicker.es.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/clockpicker/clockpicker.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/footable/footable.all.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

<script src="{{ asset('js/enrollment/create.js') }}"></script>

<script>

var redo=false;
    

document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('menu-enrollments').classList.add("active");
    document.getElementById('submenu-enrollments-2').classList.add("active");
    document.getElementById("student_document_number").focus();
    
});



$('#datepicker,#datepicker-relative').datepicker({
    format: 'dd/mm/yyyy',
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es'
});

$('#datepicker-year').datepicker({
    format: "yyyy",
    weekStart: 1,
    orientation: "bottom",
    keyboardNavigation: false,
    viewMode: "years",
    minViewMode: "years"
});



$('#period_cost').keyup(function(e){
    fee_cost();
});





$("#classroom").autocomplete({
    source:'{{ action("ClassroomController@search_classroom") }}',
    delay: 500,
    minLength:1,
    select:function(event , ui){
        $("#hidden_classroom_id").val(ui.item.id);
            
        $('#classroom').prop('readonly',true);

        $('#classroom-info > a').html('Vacantes disponibles: '+Number(ui.item.classroom.vacancy-ui.item.classroom.enrollments_count));

        $('#period_cost').val(ui.item.classroom.level.price);
        fee_cost();
    }
});

$("#student_document_number").autocomplete({
    source:'{{ action("StudentController@search_student") }}',
    //delay: 500,
    minLength:3,
    select:function(event , ui){
        $("#hidden_student_id").val(ui.item.id);

        $('#student_father_lastname').val(ui.item.student.entity.father_lastname);
        $('#student_mother_lastname').val(ui.item.student.entity.mother_lastname);
        $('#student_name').val(ui.item.student.entity.name);


        var date=ui.item.student.entity.birth_date;

        var birth_date= date.split("-");
        // var birth_date= date.split("-").reverse().join("/");

        $('#datepicker').datepicker("setDate", new Date(birth_date[0],birth_date[1]-1,birth_date[2]) );

        // console.log(birth_date);

        $('#student_telephone').val(ui.item.student.entity.telephone);

        $('#student_address').val(ui.item.student.entity.address);
        $('#student_district').val(ui.item.student.entity.district.name);
        $('#hidden_student_district_id').val(ui.item.student.entity.district.id);


        $('#student_ie').val(ui.item.student.school.name+' - '+ui.item.student.school.district.name);

        // $('#student_graduation_year').val(ui.item.student.graduation_year);

        $('#datepicker-year').datepicker("setDate", new Date(ui.item.student.graduation_year,0,1) );

        
        var base_url=$('#base_url').val();

        $('#file_data_dismiss').click();
        $('#photo_file').css('display','block');
        $('#photo_file').prop('src',base_url+'/uploads/photo_files/'+ui.item.student.photo_file);

        $('#redo-student-district').css('display','none');
        $('#redo-student-ie').css('display','none');

        $('#student_photo_file').prop('disabled',true);
        
        
        $('.student').prop('readonly',true);
        $('select.student').prop('readonly',true);


        $('#add_ie').css('display','none');
        // console.log(ui.item);
    }
});


$("#relative_document_number").autocomplete({
    source:'{{ action("RelativeController@search_relative") }}',
    //delay: 500,
    minLength:3,
    select:function(event , ui){
        $("#hidden_relative_id").val(ui.item.id);

        $('#relative_father_lastname').val(ui.item.relative.entity.father_lastname);
        $('#relative_mother_lastname').val(ui.item.relative.entity.mother_lastname);
        $('#relative_name').val(ui.item.relative.entity.name);

        var date=ui.item.relative.entity.birth_date;
        var birth_date= date.split("-");
        // var birth_date= date.split("-").reverse().join("/");
        $('#datepicker-relative').datepicker("setDate", new Date(birth_date[0],birth_date[1]-1,birth_date[2]) );
        // console.log(birth_date);

        $('#relative_telephone').val(ui.item.relative.entity.telephone);

        $('#relative_address').val(ui.item.relative.entity.address);

        $('.relative').prop('readonly',true);
        $('select.relative').prop('readonly',true);
        // console.log(ui.item);
    }
});

$("#student_district").autocomplete({
    source:'{{ action("AdministratorController@search_district") }}',
    //delay: 500,
    minLength:3,
    select:function(event , ui){
        $("#hidden_student_district_id").val(ui.item.id);
        
        $('#student_district').prop('readonly',true);
        
    }
});



$("#student_ie").autocomplete({
    source:'{{ action("SchoolController@search_ie") }}',
    //delay: 500,
    minLength:3,
    select:function(event , ui){
        $("#hidden_student_ie_id").val(ui.item.id);
        
        $('#student_ie').prop('readonly',true);
        
    }
});


$("#career").autocomplete({
    source:'{{ action("CareerController@search_career") }}',
    //delay: 500,
    minLength:3,
    select:function(event , ui){
        console.log(ui);
        $("#career_id").val(ui.item.id);
        $('#career').prop('readonly',true);
    }
});




$('input[name="payment_type"]').click(function(){ 
    // console.log($(this).val()) 
    let value=$(this).val();
    $('#hidden_payment_type_value').val(value);
    if(value=='cash'){
        $('#fees_quantity_div').css('display','none');
        $('#fee_cost_div').css('display','none');

        $('#enrollment_cost').val('0.00');

        

        // $('#').prop('required',true); 
        
    }

    if(value=='credit'){
        $('#fees_quantity_div').css('display','block');
        $('#fee_cost_div').css('display','block');

        $('#fees_quantity').unbind();
        $('#fees_quantity').keyup(function(e){
            fee_cost();
        });

        $('#enrollment_cost').val('50.00');
    }

});



function searchDoc(){
    var doc=$('#student_document_number').val();

    if(doc!=''){
        if(!redo){
            $('.spiner-3').toggleClass('sk-loading');
            $.get('{{ action("AdministratorController@quertium",["dni"=>""]) }}/'+doc, function(data){

                $('.spiner-3').toggleClass('sk-loading');

                if(data.success){
                    $('#student_document_number').val(data.person["dni"]).prop('readonly',true);
                    $('#student_father_lastname').val(data.person["apellidoPaterno"]).prop('readonly',true);
                    $('#student_mother_lastname').val(data.person["apellidoMaterno"]).prop('readonly',true);
                    $('#student_name').val(data.person["nombres"]).prop('readonly',true);
                    

                    
                    $('#s-button i').addClass('fa-redo').removeClass('fa-search');
                    $('#s-button').attr('title','Nueva busqueda');
                    redo=true;
                    
                }else{
                    $('#student_document_number').val('');
                    $('#student_document_number').focus();
                    swal("Error", 'No se ha encontrado el DNI especificado.', "error");
                }
            },"json");

        }else{
            $('#student_document_number').val('').prop('readonly',false);
            $('#student_father_lastname').val('').prop('readonly',false);
            $('#student_mother_lastname').val('').prop('readonly',false);
            $('#student_name').val('').prop('readonly',false);

            $('#s-button i').removeClass('fa-redo').addClass('fa-search');
            $('#s-button').attr('title','Buscar DNI');
            redo=false;
            $('#student_document_number').focus();
        }

    }else{
        swal("Error", 'Debe ingresar un numero de DNI', "error");
    }

}

function show_modal(){
    
    // var data={'_token':"{{ csrf_token() }}",'infractions':infractions_selected};            
    $.ajax({
        type: "post",
        url: "",           
        data    : data,    
        success: function(data){
            // console.log(data);
            setModal(data);
                         
        }                
    });      
        
}

function setModal(data){



    $('#infractions_modal_body').html(data);  
        
    $('#infractions_modal').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('.footable').footable();

    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    $('.i-checks').on('ifChanged', function(event) {

        // console.log('checked = ' + event.target.checked);

        // console.log('value = ' + event.target.value);

        // if(event.target.checked){
        //     infractions_selected.push(event.target.value);
        // }else{

        //     var index = infractions_selected.indexOf(event.target.value);
        //     if (index > -1) {
        //         infractions_selected.splice(index, 1);
        //     }

        // }
        // $('#infractions_selected').val(infractions_selected);
    });

    $('#infractions_modal').on('hide.bs.modal', function (e) {
        // console.log('hide');
        load_selected_infractions();
        

    });

}



function clean(selector_elem,selector_hidden){
    $(selector_elem).val('');
    $(selector_hidden).val(0)
    $(selector_elem).prop('readonly',false);
    $(selector_elem).focus();
}

function clean_classroom_info(selector_elem,selector_hidden){
    $(selector_elem).val('');
    $(selector_hidden).val(0)
    $(selector_elem).prop('readonly',false);
    $(selector_elem).focus();

    $('#classroom-info > a').html('');
    $('#period_cost').val('0.00');
    $('#fee_cost').val('0.00');
    
}

function clean_student_info(selector_elem,selector_hidden){
    $(selector_elem).val('');
    $(selector_hidden).val(0)
    $(selector_elem).prop('readonly',false);
    $('#student_document_number').focus();

    // $('#student_licence_type_id').prop('disabled',false);
    $('#redo-student-district').css('display','block');
    $('#redo-student-ie').css('display','block');
    $('#student_photo_file').prop('disabled',false);
    $('#photo_file').prop('src','');
    $('#photo_file').css('display','none');
    $('#add_ie').css('display','inline');
}

function clean_relative_info(selector_elem,selector_hidden){
    $(selector_elem).val('');
    $(selector_hidden).val(0)
    $(selector_elem).prop('readonly',false);
    $('#relative_document_number').focus();

    
}

function fee_cost(){
    var period_cost=$('#period_cost').val();
    var fees_quantity=$('#fees_quantity').val();
    var fee_cost=0.00;

    if( period_cost!='' && fees_quantity!=0){
        fee_cost=Number( Number(period_cost) / Number(fees_quantity) ).toFixed(2);
    }
    
    $('#fee_cost').val(fee_cost);
}

function previewFile() {
    $('#photo_file').css('display','block');

    var preview = document.querySelector('img');
    var file    = document.querySelector('input[type=file]').files[0];

    
    var reader  = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        $('#photo_file').css('display','none');
    }

    
}



$('#create-form').submit(function(e){
    e.preventDefault();

    var student_valid=false;
    var classroom_valid=false;

    var student_id=$('#hidden_student_id').val();
    var classroom_id=$('#hidden_classroom_id').val();

    if(student_id == 0){
        var student_district_id=$('#hidden_student_district_id').val();
        if(student_district_id != 0){
            student_valid=true;
        }else{
            student_valid=false;
            swal("Error", 'Debe selecionar distrito del Alumno.', "error");
        }

        var student_ie_id=$('#hidden_student_ie_id').val();

        if(student_ie_id != 0){
            student_valid=true;
        }else{
            student_valid=false;
            swal("Error", 'Debe selecionar I.E. de procedencia del Alumno.', "error");
        }

    }else{
        student_valid=true;
    }
    

    // if(relative_id != 0){
    //     relative_valid=true;
    // }else{
    //     swal("Error", 'Debe selecionar un Apoderado.', "error");
    // }

    if(classroom_id != 0){
        classroom_valid=true;
    }else{
        classroom_valid=false;
        swal("Error", 'Debe selecionar un Ciclo/Nivel/Aula.', "error");
    }

    

    

    if( student_valid && classroom_valid){
        $('.ibox').children('.ibox-content').toggleClass('sk-loading');

        $('#create-form').unbind();

        
        setTimeout(function(){
            $('#submit').click();
        },1000)
    }
    
    

});


// ------------------------------------------


function show_add_school_modal(){

    $.ajax({
        type: "get",
        url: "{{ action('SchoolController@create') }}",     
        success: function(data){
            setModal_addSchool(data);             
        }                
    });  

}

function setModal_addSchool(data){

    $('#add_school_modal_body').html(data);  

    $('#add_school_modal').on('shown.bs.modal',function(){
        $('#school_name').focus();
    });

    $('#add_school_modal').modal({
        backdrop: 'static',
        keyboard: false
    });       

    $("#school_district").unbind();
    $("#school_district").autocomplete({
        source:'{{ action("AdministratorController@search_district") }}',
        //delay: 500,
        minLength:3,
        select:function(event , ui){
            $("#hidden_school_district_id").val(ui.item.id);
            
            $('#school_district').prop('readonly',true);
            
        }
    });



    $('#school_form').unbind();
    $('#school_form').submit(function(e){
        e.preventDefault();
        
        var url = $(this).attr('action');
        var form_data = $(this).serialize();

        $.ajax({
            type: "post",
            url: url,   
            dataType:'json',             
            data    : form_data,   
            success: function(data){
                console.log(data);
                if(data.success){
                    $('#add_school_modal').modal('hide');
                    $('#student_ie').val(data.school.name+' - '+data.school.district.name);
                    $('#student_ie').attr('readonly',true);
                    $('#hidden_student_ie_id').val(data.school.id);
                    
                }else{
                    alert('Error');
                }
                
                    
            }
        });

    });

}



</script>


@endsection