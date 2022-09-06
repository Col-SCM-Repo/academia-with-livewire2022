@extends('layouts.app')

@php
$slot = ''
@endphp

@push('styles')
    <link href="{{ asset('inspinia_admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">


    <style>
        /* .wizard .content {
            min-height: 100px;
        }
        .wizard .content > .body {
            width: 100%;
            height: auto;
            padding: 15px;
            position: absolute;
        }
        .wizard .content .body.current {
            position: relative;
        } */

        .typeahead   > li  {
            list-style:none;
            font-size:12px;
        }

    </style>
@endpush

@push('scripts')
    <!-- Steps -->
    <script src="{{ asset('inspinia_admin/js/plugins/steps/jquery.steps.min.js') }}"></script>
    <!-- Jquery Validate -->
    <script src="{{ asset('inspinia_admin/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <!-- Data picker -->
    {{-- <script src="{{ asset('inspinia_admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script> --}}
    <!-- Data table -->
    {{-- <script src=" {{ asset('inspinia_admin/js/plugins/dataTables/datatables.min.js') }} "></script> --}}

    <!-- Typehead -->
    <script src="{{ asset('inspinia_admin/js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>

    <script>
        /* var settings = {
            stepsOrientation: $.fn.steps.stepsOrientation.horizontal,
            autoFocus: false,
            enableAllSteps: true,
            enableKeyNavigation: true,
            enablePagination: false,
            suppressPaginationOnFocus: true,
            enableContentCache: true,
            enableCancelButton: true,
            enableFinishButton: true,
            preloadContent: false,
            showFinishButtonAlways: false,
            forceMoveForward: false,
            saveState: false,
            startIndex: 0,
            transitionEffect: $.fn.steps.transitionEffect.slideLeft,
            transitionEffectSpeed: 200,

            onStepChanging: function (event, currentIndex, newIndex) {
                return true; },
            onStepChanged: function (event, currentIndex, priorIndex) {

            },
            onCanceled: function (event) {

            },
            onFinishing: function (event, currentIndex) {
                return true; },
            onFinished: function (event, currentIndex) {

                toastr.success('Se creo la matricula correctamente', 'Alerta')
            }
        };
        $("#wizard").steps(settings);

        Livewire.on('wizzard-step', step => {
            $("#wizard").steps(step);
        } ); */
    </script>
@endpush


@section('header')
<div class="col-md-5">
    <h2>Nueva matricula</h2>
    <ol class="breadcrumb">
        <li>
            <a>Home</a>
        </li>
        <li>
            <a>Matricula</a>
        </li>
        <li class="active">
            <strong>Nueva matricula</strong>
        </li>
    </ol>
</div>
<div class="col-md-7 text-right" style="padding-top: 1rem">
    <button class="btn btn-sm btn-success din" type="button" id="btnResetComponents">
        Limpiar formulario
    </button>
</div>

@endsection

@section('content')
    <div class="row" >
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content" style="padding: 0">
                    <div class="tabs-nueva-matricula " style="min-height: 85vh">
                        <ul class="nav nav-tabs">
                            <li class=" active"><a data-toggle="tab" href="#tab-1"> ALUMNO</a></li>
                            <li class=" "><a data-toggle="tab" href="#tab-2">APODERADO</a></li>
                            <li class=" "><a data-toggle="tab" href="#tab-3">MATRICULA</a></li>
                            <li class=" "><a data-toggle="tab" href="#tab-4">PAGOS</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="panel-body ">
                                    <!-- Begin: contenido alumno -->
                                        @livewire('matricula.alumno')
                                    <!-- End: contenido alumno -->
                                </div>
                            </div>
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body ">
                                    <!-- Begin: contenido apoderado -->
                                        @livewire('matricula.apoderado')
                                    <!-- End: contenido apoderado -->
                                </div>
                            </div>
                            <div id="tab-3" class="tab-pane">
                                <div class="panel-body ">
                                    <!-- Begin: contenido matricula -->
                                        @livewire('matricula.matricula')
                                    <!-- End: contenido matricula -->
                                </div>
                            </div>
                            <div id="tab-4" class="tab-pane">
                                <!-- Begin: contenido pagos -->
                                        @livewire('matricula.pago')
                                <!-- End: contenido pagos -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(()=>{
            $('#btnResetComponents').on('click', ()=>{
                alert("reseteando componentes");
                Livewire.emit('reset-forms', true);
            })
        })
    </script>
@endpush

