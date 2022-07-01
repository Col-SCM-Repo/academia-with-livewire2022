@extends('templates.inspinia_template')


@section('title','Buscar Matricula')

@section('styles')
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/sweetalert/sweetalert.css') }}"
    rel="stylesheet">

<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/footable/footable.core.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

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
        

        .info-alumno {
                width: 100%; 
                max-width: 250px;
                padding: 16px;
        }

        tbody tr:hover{
            background: #FFF689 !important;
            color: #000;
            cursor: pointer;
        }


        @media (max-width: 1200px) {
            .info-alumno {
                width: 100%; 
                max-width: 1000px;
            }
        }
        
    </style>
@endsection


@section('content')
<div class="container-fluid" id="app">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox animated bounceInDown" >
                <div class="ibox-content">
                    <div class="row" >
                        <div class="col-12 pt-1 pb-1 ">
                            <h3 class="text-uppercase">Datos del alumno y incidentes</h3>
                        </div>
                        

                        <!------------------------- Begin:Container alumnos-incidentes ------------------------->
                        <div class="col-12 d-flex  flex-wrap justify-content-center">
                            <!------------------------- Begin:Container alumnos ------------------------->
                            <div class="info-alumno  border border-1 shadow col-3">
                                <!-- Informacion del alumno -->
                                <!--Avatar -->
                                <div class="w-100 p-md-2  pt-3 text-center" >
                                    <img class="w-100" style="border-radius: 100%; max-width: 180px;" src="{{ asset('uploads/photo_files/'.$enrollment->student->entity->photo_path) }}" alt="Foto de alumno">
                                </div>
                                <!-- Informacion del alumno -->
                                <div class="p-2 pt-3 pb-3 d-flex justify-content-center">
                                    <div class="row " style="max-width: 300px;">
                                        <div class="col-4 text-capitalize"><strong>Nombre:</strong> </div>
                                        <div class="col-8"> @{{ data?.student.entity.name }} </div>
                                        <div class="col-4 text-capitalize"><strong>Apellidos:</strong> </div>
                                        <div class="col-8"> @{{ data?.student.entity.father_lastname + ' ' + data?.student.entity.mother_lastname }}  </div>
                                        <div class="col-4 text-capitalize"><strong>Nivel:</strong> </div>
                                        <div class="col-8"> @{{ data?.classroom.level.level_type.description }} </div>
                                        <div class="col-4 text-capitalize"><strong>Aula:</strong> </div>
                                        <div class="col-8"> @{{ data?.classroom.name }} </div>
                                        <div class="col-4 text-capitalize"><strong>Ciclo:</strong> </div>
                                        <div class="col-8"> @{{ data?.classroom.level.period.name }} </div>
                                    </div>
                                </div>
                                <!-- Begin: Acciones -->
                                <div class="">
                                    <h4>Opciones:</h4>
                                    <div class="d-flex flex-wrap justify-content-center">
                                        <a  href="{{ action('EnrollmentController@edit', ['id'=>$enrollment->id]) }}" target="_blank"  class="btn btn-primary btn-sm m-1" style="background: #347AB7" type="button"  > <i class="fa fa-file" aria-hidden="true"></i> Matricula</a>
                                        <button class="btn btn-primary btn-sm m-1" style="background: #347AB7" type="button" v-on:click="clickBtnNuevoIncidente" > <i class="fa fa-plus" aria-hidden="true"></i> incidente</button>
                                        <button class="btn btn-primary btn-sm m-1" style="background: #347AB7"   type="button" v-on:click="onClickDonwload()" > 
                                            Reporte <i class="fa fa-download" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <!-- Modales -->
                                    @include('incidentes.modal.incidente')
                                    @include('incidentes.modal.evidencias')
                                    @include('incidentes.modal.download-evidencias')
                                </div>
                                <!-- End: Acciones -->
                            </div>
                            <!------------------------- Begin:Container alumnos ------------------------->
                            <!------------------------- Begin:Container incidentes ------------------------->
                                <!--Incidencias -->
                            <div class="shadow pl-4 col-9"  >
                                <h3>Incidentes</h3>
                                <div class="bg-white">
                                    <table class="table table-light col-12" style="font-size: 0.875em; overflow: visible;">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th>Incidente</th>
                                                <th style="width: 100px;" >Reporto</th>
                                                <th>Justificacion</th>
                                                <th>Parentesco</th>
                                                <th style="width: 100px;">Justificó</th>
                                                <th style="width: 90px;" class="text-center" >F. creacion</th>
                                                <th style="width: 90px;" class="text-center" >F. Reporte</th>
                                                <th style="width: 50px;" class="text-center" >Evidencias</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="data?.incidentes">
                                                <template v-for="objInc in data.incidentes">
                                                    <template v-if="objInc.estado=='0' ">
                                                        <tr style="background: #F4D1AE ;;"  >
                                                            <td v-on:click="onclickIncidente(objInc.id)" > @{{ objInc.descripcion }}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)" > @{{ objInc.auxiliar.entity.name }} @{{objInc.auxiliar.entity.father_lastname}}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)"  class="text-center" > - </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)"  class="text-center" > - </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)"  class="text-center" > - </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)"  class="text-center" > @{{ objInc.created_at }}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)"  class="text-center" > @{{ objInc.fecha_reporte }}  </td>
                                                            <td class="text-center" > - </td>
                                                        </tr>
                                                    </template>
                                                    <template v-else>
                                                        <tr>
                                                            <td v-on:click="onclickIncidente(objInc.id)" > @{{ objInc.descripcion }}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)" > @{{ objInc.auxiliar.entity.name }} @{{ objInc.auxiliar.entity.father_lastname }}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)" > @{{ objInc.justificacion }}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)" > @{{ objInc.parentesco }}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)" > @{{ objInc.secretaria.entity.name }} @{{ objInc.secretaria.entity.father_lastname }}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)"  class="text-center" > @{{ objInc.created_at }}  </td>
                                                            <td v-on:click="onclickIncidente(objInc.id)"  class="text-center" > @{{ objInc.fecha_reporte }}  </td>
                                                            <td  class="text-center" style="color: #25852A;"> 
                                                                <button class="btn border-0 bg-transparent text-primary p-0 m-0 px-2" v-on:click="clickBtnEnvidencias(objInc.id)">
                                                                    <span style="font-size: 1.5em;"><i class="fa fa-file" aria-hidden="true"></i></span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </template>
                                                <template v-if="data.incidentes.length == 0 ">
                                                    <tr>
                                                        <td colspan="8"> 
                                                            No se ha encontrado registros 
                                                            @{{ data?.incidentes.length }}
                                                        </td>
                                                    </tr>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <tr>
                                                    <td colspan="8"> 
                                                        Cargando... 
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!------------------------- End:Container incidentes ------------------------->
                        </div>
                        <!------------------------- End:Container alumnos-incidentes ------------------------->
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
    <input type="hidden" id="matricula_id" value="{{ $enrollment->id }}">
    <input type="hidden" id="baseUrl" value="{{ asset('/') }}">
</div>


@endsection



@section('scripts')

<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/footable/footable.all.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/sweetalert/sweetalert.min.js') }}"></script>


<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('menu-incidentes').classList.add("active");
        document.getElementById('submenu-incidentes-2')?.classList.add("active");
    })

    /* EJEMPLO DE PETICION */
    function search_enrollment(){
        var param=$('#input-search').val();
        if(param != ''){
            $('#result-panel').css('display','block');
            $('#result-panel > .ibox').children('.ibox-content').toggleClass('sk-loading');
            $.ajax({
                type: "get",
                url: "{{ action('IncidenteController@search_enrollment',['param'=>'']) }}/"+param,
                success: function(data){
                    $('#result-panel').html(data); 
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
<script src="{{ asset('js/incidentes/index.js') }}"></script>


<script>
    /*************************  Super logica para los checkbox *************************/
    const cbxTodos = document.getElementById('cbx-todos');
    const cbxInasistencia = document.getElementById('cbx-inasistencia');
    const cbxTardanza = document.getElementById('cbx-tardanza');
    const cbxComportamiento = document.getElementById('cbx-comportamiento');
    const cbxNotas = document.getElementById('cbx-notas');
    

    cbxTodos.addEventListener('change', (e)=>{
            cbxInasistencia.checked = e.target.checked;
            cbxTardanza.checked = e.target.checked;
            cbxComportamiento.checked = e.target.checked;
            cbxNotas.checked = e.target.checked;
    });

    function changeOtherOptions (event){
            if(
                cbxInasistencia.checked &&
                cbxTardanza.checked &&
                cbxComportamiento.checked &&
                cbxNotas.checked
            )
                cbxTodos.checked = true;
            else
                cbxTodos.checked = false;
    }
    
    cbxInasistencia.addEventListener('change', changeOtherOptions);
    cbxTardanza.addEventListener('change', changeOtherOptions);
    cbxComportamiento.addEventListener('change', changeOtherOptions);
    cbxNotas.addEventListener('change', changeOtherOptions);
    
    //console.log("cbxTodos", cbxTodos , "cbxInasistencia", cbxInasistencia , "cbxTardanza", cbxTardanza , "cbxComportamiento", cbxComportamiento , "cbxNotas", cbxNotas)

    
    /*   PERIODO */
    const f_inicio = document.getElementById('f_inicio');
    const f_fin    = document.getElementById('f_fin');
    
    document.getElementById('todo-periodo').addEventListener('change', (event)=>{
        f_inicio.disabled = event.target.checked;
        f_fin.disabled = event.target.checked;
    });


</script>



<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script>
    $( "#evidencias-modal" ).draggable();
    $( "#incidentes-modal" ).draggable();
</script>



@endsection



