@extends('templates.inspinia_template')


@section('title','Buscar Matricula')

@section('styles')

<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/footable/footable.core.css') }}" rel="stylesheet">
<style>
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }
    
        /* .spiner-example{
            display: block;
            position: absolute;
            right: 0%;
            height: 100%;
            z-index: 100;
            background-color: azure;
            width: 100%;
            opacity: 0.6;
            top: 0%;
        } */
    </style>
@endsection


@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox animated bounceInDown" >
                {{-- <div class="ibox-title">
                    <h5>Busqueda de Actas </h5>        
                </div> --}}

                
                
                <div class="ibox-content">

                    <h2>Busqueda de Matricula </h2>
                    
                    <div class="col-md-8">


                    <div class="input-group">
                        <input id="input-search" type="text"  placeholder="Buscar Matricula por Nombre o DNI" class="input form-control" value="{{$get_value}}">
                        <span class="input-group-append">
                            <button id="search-button" type="button" class="btn btn btn-primary" onclick="search_enrollment()"> <i class="fa fa-search"></i> Buscar</button>
                        </span>
                    </div>

                    </div>


                </div>


                
            </div>





            </div>


            <div id="result-panel" class="col-lg-12" style="display:none;">    
                    
                        
                <div class="ibox animated bounceInDown"> 
                    <div class="ibox-content" style="height:500px">
                        <div class="sk-spinner sk-spinner-wave" >
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                    </div>
                </div>
            
                    
                        
            </div>


        
        </div>
    </div>
</div>


<div class="modal inmodal fade" id="infractions_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">INFRACCIONES</h4>
                <small class="font-bold">Agregue infracciones a esta acta.</small>
            </div>
            <div id="infractions_modal_body" class="modal-body">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Aceptar</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@endsection



@section('scripts')

<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/footable/footable.all.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/iCheck/icheck.min.js') }}"></script>

<script>

    var infractions_selected=null;
    var new_infractions_selected=[];

    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('menu-enrollments').classList.add("active");
        document.getElementById('submenu-enrollments-1').classList.add("active");
        // arrobaif($get_value != null)
        //     search_record();
        // arrobaendif
    })

    

    function search_enrollment(){
        
        var param=$('#input-search').val();
        if(param != ''){
            $('#result-panel').css('display','block');
            // $('#result-panel').children('.ibox-content').html(''); 
            // $('#result-panel > .ibox').children('.ibox-content').css('height','500px');
            $('#result-panel > .ibox').children('.ibox-content').toggleClass('sk-loading');
            $.ajax({
                type: "get",
                url: "{{ action('EnrollmentController@search_enrollment',['param'=>'']) }}/"+param,
                success: function(data){
                    // console.log(data);
                    // $('#result-panel').children('.ibox-content').css('height','100%');
                    $('#result-panel').html(data); 
                    // $('#result-panel > .ibox').children('.ibox-content').toggleClass('sk-loading');
                
                    
                }, 
                error: function(err){
                    console.error(err);
                }             
            }); 
        }else{
            alert('Ingrese un numero de acta.');
        }
                  
             
            
    }

 

  

</script>



@endsection