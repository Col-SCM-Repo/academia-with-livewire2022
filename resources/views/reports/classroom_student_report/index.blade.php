@extends('templates.inspinia_template')


@section('title','Reporte de Alumnos por Aula')

@section('styles')


<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">



<style>
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }


</style>
@endsection


@section('heading')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2>RReporte de Alumnos por Aula</h2>
        </div>
    </div>


@endsection




@section('content')


<div class="container">
    <div class="row">
        <div class="col-lg-12">
            
                <div class="ibox animated bounceInDown">
                        {{-- <div class="ibox-title">
                            <h5><i class="fa fa-calendar"></i> REPORTE DE ASISTENCIA</h5>        
                        </div> --}}

                        
                        
                        <div class="ibox-content" id="div-content">
                            
                            <div class="sk-spinner sk-spinner-rotating-plane"></div>
                            
                            
                            <form id="report-form" action="{{action('StudentController@do_classroom_students')}}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <strong>Ciclo</strong>
                                            <select name="period_id" id="period_id" class="form-control" required>
                                                <option value="">Seleccione un ciclo</option>
                                                @foreach ($periods as $period)
                                                    <option value="{{$period->id}}">{{$period->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <strong>Nivel</strong>
                                            <select name="level_id" id="level_id" class="form-control" required>
                                                <option value="">Seleccione un nivel</option>
                                                
                                            </select>
                                        </div>
                                    
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <strong>Aula</strong>
                                            <select name="classroom_id" id="classroom_id" class="form-control" required>
                                                <option value="">Seleccione un aula</option>
                                                
                                            </select>
                                        </div>
                                    
                                    </div>
                                    
            
                                    <div class="col-sm-2">   
                                        <br>
                                        <button class="btn btn-success" type="submit"><i class="fa fa-search"></i> Buscar</button>                  
                                    </div>
            
                                    <div class="col-sm-12">   
                                        <div class="table-responsive">
                                        
                                        </div>
                                    </div>
            
                                </div>
                            </form>
                           
                        </div>
                        
                    </div>
            
        </div>
    </div>
</div>



@endsection



@section('scripts')

<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/dataTables/datatables.min.js') }}"></script>

<script>

       
    
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('menu-reports').classList.add("active");
            document.getElementById('submenu-reports-1').classList.add("active");
            
        });
    
    
        $('#period_id').change(function(){
            var value=$(this).val()
            
            if(value != ""){
                $('.ibox').children('.ibox-content').toggleClass('sk-loading');

                $.ajax({
                    type: "get",
                    url: "{{action('PeriodController@level_per_period')}}?period_id="+value,   
                    dataType:'json',   
                    success: function(data){
                        // console.log(data);
                        $("#level_id").html('');
                        $("#level_id").append(new Option('Seleccione un nivel',''));
                        for(var k in data) {
                            // console.log(data[k]);
                            $("#level_id").append(new Option(data[k].level_type.description,data[k].id));
                        }
                        $('.ibox').children('.ibox-content').toggleClass('sk-loading');
                    }
                });

            }else{
                $("#level_id").html('');
                $("#level_id").append(new Option('Seleccione un nivel',''));

                $("#classroom_id").html('');
                $("#classroom_id").append(new Option('Seleccione un aula',''));
            }


        });


        $('#level_id').change(function(){
            var value=$(this).val()
            
            if(value != ""){
                $('.ibox').children('.ibox-content').toggleClass('sk-loading');

                $.ajax({
                    type: "get",
                    url: "{{action('ClassroomController@classroom_per_level')}}?level_id="+value,   
                    dataType:'json',   
                    success: function(data){
                        // console.log(data);
                        $("#classroom_id").html('');
                        $("#classroom_id").append(new Option('Seleccione un aula',''));
                        for(var k in data) {
                            // console.log(data[k]);
                            $("#classroom_id").append(new Option(data[k].name,data[k].id));
                        }
                        $('.ibox').children('.ibox-content').toggleClass('sk-loading');
                    }
                });

            }else{

                $("#classroom_id").html('');
                $("#classroom_id").append(new Option('Seleccione un aula',''));
            }


        });
        

        $('#report-form').submit(function (event) {
            event.preventDefault();

        
        
            $('.ibox').children('.ibox-content').toggleClass('sk-loading');
            var form_data = $(this).serialize(),

            url = $(this).attr('action');

            $.ajax({
                type: "post",
                url: url,   
                // dataType:'json',             
                data: form_data, 
                success: function(data){
                    console.log(data);
                    $('.ibox').children('.ibox-content').toggleClass('sk-loading');
                    $('.table-responsive').html(data);
                    $('.dataTables').DataTable({
                        pageLength: 100,
                        ordering: false,
                        dom: '<"html5buttons"B>ftp',   
                        buttons: [
                            {
                                extend: 'excel', 
                                footer: true,
                                title: 'Reporte Alumnos por aula',
                                // exportOptions : {
                                //     columns: [ 0, 1, 2, 3, 4]                        
                                // },
                                
                            }
                        ],         
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
           }); 

       

    });  

       
        
    
        
    
    </script>


@endsection