@extends('layouts.app')
@php
$slot = ''
@endphp

@push('styles')
<link href="{{ asset('inspinia_admin/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src=" {{ asset('inspinia_admin/js/plugins/dataTables/datatables.min.js') }} "></script>
@endpush


@section('header')
    <div class="col-md-8">
        <h2>Revisión de examenes</h2>
        <ol class="breadcrumb">
            <li>
                <a href="">Home</a>
            </li>
            <li>
                <a>Evaluaciones</a>
            </li>
            <li class="active">
                <strong>Carga y revisión de examenes</strong>
            </li>
        </ol>
    </div>
    <div class="col-md-4 text-right " style="padding-top: 2rem" >
        @livewire('common.ciclo-select')
    </div>
@endsection

@section('content')

<div class="ibox">
    <div class="ibox-content" style="padding: 0">
        <div class="tabs-nueva-matricula " style="min-height: 85vh">
            <ul class="nav nav-tabs">
                <li class=" active"><a data-toggle="tab" href="#tab-1"> CODIGOS EXAMEN</a></li>
                <li class=" "><a data-toggle="tab" href="#tab-2">REVISIÓN DE EXAMEN</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body ">
                        <!-- Begin: codigos examen -->
                            @livewire('evaluacion.revision-codigos')
                        <!-- End: codigos examen -->
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body ">
                        <!-- Begin: revision examenes -->
                            @livewire('evaluacion.revision-examen')
                        <!-- End: revision examenes -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
