@extends('templates.inspinia_template')

@section('styles')

<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">


@endsection


@section('heading')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2>Gestión de Ciclos / Periodos / Aulas</h2>
        </div>
    </div>


@endsection

@section('content')


<div class="container">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox animated bounceInDown">
                <div class="ibox-title">
                    <h5><i class="fa fa-calendar"></i> CICLOS</h5>        
                </div>
                
                <div class="ibox-content">
                    <div class="sk-spinner sk-spinner-rotating-plane"></div>
                    <div class="panel panel-primary" style="display: none">
                        <div class="panel-heading edit-success">
                            <strong style="font-size: 15px"><i class="fa fa-check"></i> <span id="success-message"></span></strong>  
                        </div>                            
                    </div>
                    <div class="row">
                        <div class="col-sm-9 m-b-xs">
                            <a href="{{action('PeriodController@create')}}" class="btn btn-success" id="create-period"><i class="fa fa-plus"></i> Nuevo Ciclo</a>
                        </div>
                        <div class="col-sm-3">                            
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('periods.partials.listing')
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



@endsection

@section('scripts')
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/dataTables/datatables.min.js') }}"></script>

<script>

    

    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('menu-maintenance').classList.add("active");
        document.getElementById('submenu-maintenance-1').classList.add("active");

    });
   
    
    set_dataTable();

    function status(id,status){
        arr={id:id,status:status,_token:"{{ csrf_token() }}"}

        swal({
            title: "¿Ésta seguro?",
            text: (status==1?'Este ciclo será establecido como Vigente':'Este ciclo será establecido como Inhabilitado'),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: 'Aceptar',
            showLoaderOnConfirm: true
        }, function () {

            $.ajax({
                type: "post",
                dataType:'json',       
                data: arr,
                url: "{{action('PeriodController@status')}}",
                success: function(data){
                    swal({
                        title: 'Correcto',
                        text: "El ciclo fue establecido como Vigente",
                        type: "success",                        
                    }, function () {
                        list_periods();
                    });
                }                
            });
           
        });
        
    }

    function set_dataTable(){
        $('.dataTables').unbind();
        $('.dataTables').DataTable({
            pageLength: 10,
            ordering: false,
            dom: 'ftp',            
            language: {
                search:         "Buscar&nbsp;:",    
                zeroRecords:    "No se han encontrado elementos para la busqueda.",
                paginate: {
                    first:      "Primera",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Ultima"
                },       
            }
        });
    }  


    function list_periods(){
        $('.ibox').children('.ibox-content').toggleClass('sk-loading');
        $.get("{{action('PeriodController@listing')}}", function(data) {
		    $(".table-responsive").html(data);
            set_dataTable();
            $('.ibox').children('.ibox-content').toggleClass('sk-loading');
	    });
    }

     

    
    // function form_create(){
    //     $.ajax({
    //         type: "get",
    //         url: "{{action('PeriodController@create')}}",        
    //         success: function(data){
    //             $('#modal-content').html(data);  
    //             $('#employee_modal').modal('show'); 

    //             $("input[name='radio-schedule-type']").click(function(){
    //                 var value=$("input[name='radio-schedule-type']:checked").val();
    //                 $("#hidden_schedule_type").val(value);
    //             }); 
                    
    //             $('#create-form').submit(function (event) {
    //                 event.preventDefault();
    //                 $('.sk-spinner').css('display','block');
    //                 var form_data = $(this).serialize(),

    //                 // var form_data = new FormData(document.getElementById("create-form"));
    //                 // jQuery.each(jQuery('#file')[0].files, function(i, file) {
    //                 //     form_data.append('photograph', file);
    //                 // });

    //                 url = $(this).attr('action');
                        
    //                 $.ajax({
    //                     type: "post",
    //                     url: url,   
    //                     dataType:'json',             
    //                     data: form_data,                         
    //                     // cache: false,
    //                     // contentType: false,
    //                     // processData: false,
    //                     success: function(data){
    //                         if(data.success=='true'){
    //                             $('#employee_modal').modal('hide');  
    //                             list_employees();   
    //                             $('#success-message').html(data.message);  
    //                             $('.panel-primary').fadeIn();
    //                             setTimeout(function(){
    //                                 $('.panel-primary').fadeOut();
    //                             },3000);                 
    //                         }else{
    //                             $('.sk-spinner').css('display','none');
    //                             $('.description-error').html(data.message);
    //                             $('.panel-danger').fadeIn();

    //                             setTimeout(function(){
    //                                 $('.panel-danger').fadeOut();
    //                             },3000);
    //                         }                    
    //                     }                
    //                 });
    //             });   
                        
    //         }                
    //     });
    // }

</script>
@endsection