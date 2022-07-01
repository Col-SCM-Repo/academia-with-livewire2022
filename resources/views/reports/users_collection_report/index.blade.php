@extends('templates.inspinia_template')


@section('title','Reporte de Recaudo por Usuario')

@section('styles')


<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">


<style>
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }


</style>
@endsection


@section('heading')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2>Reporte de Recaudo por Usuario</h2>
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
                            
                            
                            <form id="report-form" action="{{action('PaymentController@do_users_collection')}}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <strong>Periodo</strong>
                                            <div class="input-daterange input-group" id="datepicker">
                                                <span class="input-group-addon" style="border-width: thin">Desde</span>
                                                <input type="text" class="input-sm form-control" name="from" autocomplete="off" value="{{date('d/m/Y')}}" readonly style="z-index: 0">
                                                <span class="input-group-addon">Hasta</span>
                                                <input type="text" class="input-sm form-control" name="to" autocomplete="off" value="{{date('d/m/Y')}}" readonly style="z-index: 0">
                                            </div>
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
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/datapicker/bootstrap-datepicker.es.min.js') }}"></script>

<script>

       
    
      

        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('menu-reports').classList.add("active");
            document.getElementById('submenu-reports-2').classList.add("active");
            
        });
    
    
    
        $('.input-daterange').datepicker({
            format: 'dd/mm/yyyy',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            language: 'es'
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
                                title: 'Reporte Recaudo por Usuario',
                                
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