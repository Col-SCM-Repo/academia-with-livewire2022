@extends('templates.inspinia_template')


@section('title','Report')

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
    
        .info-alumno {
                width: 100%; 
                max-width: 250px;
                padding: 16px;
        }

        .items:hover{
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
        
        select {
            border: none;
            border-bottom: 1px solid #aaa;
            background: #fff;
        }

    </style>
@endsection


@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox animated bounceInDown" >
                <div class="ibox-content">
                    <div class="row" >
                        <div  class="col-12 col-md-10 mx-auto p-3">
                            <div>
                                <label for="tipo_report"> 
                                    Seleccione tipo de reporte
                                </label>
                                <select name="tipo_report" id="tipo_report" class="ml-3">
                                    <option value="general" >GENERAL</option>
                                    <option value="especifico">ESPECIFICO</option>
                                </select>
                            </div>

                            <div class="p-1">
                                <form id="general" class="col-sm-10 mx-auto " action="{{ action('ReportController@incidentesGeneral') }}" method="POST">
                                    @csrf
                                    <div class="mt-3 row">
                                        <label for="select-nivel" class="col-sm-3 text-right ">Nivel</label>
                                        <div class="col-sm-7">
                                            <select name="nivel" class=" w-100 form-select form-select-lg" id="select-nivel" required>
                                                <option selected>-Seleccione una opcion-</option>
                                                @foreach ($niveles as $nivel)
                                                    <option value="{{$nivel->id}}">{{$nivel->description}}</option>
                                                @endforeach
                                            </select>    
                                        </div>
                                    </div>

                                    <div class="mt-3 row">
                                        <label for="select-grado" class=" col-sm-3 text-right ">Aula</label>
                                        <div class="col-sm-3 ">
                                            <select name="grado" class=" w-100 form-select form-select-lg" id="select-grado" required>
                                            </select>    
                                        </div>
                                        
                                    </div>
                                    <div class="text-right col-12 mt-3">
                                        <button type="submit" class="btn btn-primary pl-5 pr-5"  >
                                            Buscar
                                        </button>
                                    </div>
                                </form>

                                <form id="especifico" class="my-4 col-sm-10 mx-auto" action="{{ action('ReportController@incidentesEspecifico') }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input id="input-search" type="text"  placeholder="Buscar Matricula por Nombre o DNI" class="input form-control">
                                        <span class="input-group-append">
                                            <button type="submit" id="search-button"  class="btn btn btn-primary" > <i class="fa fa-search"></i> Buscar</button>
                                        </span>
                                    </div>
                                    <!--Contenedor de alumnos : search -->
                                    <div id="resultados-Busqueda"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<!-- Comportamiento -->
<script>
    
    document.getElementById('especifico').classList.add('d-none');

    document.getElementById('tipo_report').addEventListener('change', (e)=>{
        //alert('change', e.target.checked? 'true': 'false')
        if(e.target.value == 'general' ){
            document.getElementById('general').classList.remove('d-none');
            document.getElementById('especifico').classList.add('d-none');
        }else{
            document.getElementById('general').classList.add('d-none');
            document.getElementById('especifico').classList.remove('d-none');
        }
    })

    document.getElementById('select-nivel').addEventListener('change', (e)=>{
        alert( 'haciendo peticion', e.target.value);
        
        const url = '';
        cost body = new FormData();

        

        axios(url, { method: 'GET', body}).then((  ) => {
            
        }).catch((err) => {
            console.error(err)
        })

    });

</script>

<!-- PETICIONES -->
<script>
    document.getElementById('general').addEventListener('submit', (e)=>{
        alert("general");
        e.preventDefault();
        const url = e.target.getAttribute('action');
        const data = new FormData(e.target);
        console.log(data);
        axios(url, {method:'POST', data}).then((response) => {
            console.log(response.data);
        }).catch((err) => {
            console.error(err);
        })
        console.log(e.target);
    });

    document.getElementById('especifico').addEventListener('submit', (e)=>{
        alert("especifico");
        e.preventDefault();
        const url = e.target.getAttribute('action');
        const data = new FormData(e.target);
        console.log(data);
        axios(url, {method:'POST', data}).then((response) => {
            console.log(response.data);
        }).catch((err) => {
            console.error(err);
        })
        console.log(e.target);
    });


    /*
    // Almacenando en LocalStorage 
     (La informacion dura hasta que se limpie el cache) - ambito:solo una pestaña
     localStorage.setItem('nombre', 'un valor');
     localStorage,removeItem('nombre');
     localStorage,getItem('nombre');
     localSorage.clear();    
     
     
     // Almacenando en SessionStorage
    (La informacion solo dura mientras el navegador o pestaña estan abiertos)  - ambito:todo
    sessionStorage.setItem('variable', 'valor');
    sessionStorage.getItem('variable');
    sessionStorege.removeItem('variable');


    //Cookies
    (La informacion dura el tiempo que el usuario defina al crearla)
    document.cookie = "variable=valor; expires=Thu, 31 Dec 2020 12:00:00 UTC; path=/ max-age=60*60*24*30"
    document.cookie
    document.cookie = "variable=valor; expires=Thu, 31 Dec 2010 12:00:00 UTC; path=/ max-age=60*60*24*30"



*/
</script>

<script src="{{ asset('js/app.js') }}"></script>

@endsection


