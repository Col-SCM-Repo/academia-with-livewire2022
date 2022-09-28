@extends('layouts.app')
@php
$slot = ''
@endphp

@push('styles')
<link href="{{ asset('inspinia_admin/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
<style>
    #cabecera-pagina {
        display: none;
    }

    .col-lg-12>.wrapper-content {
        padding-top: 0
    }

    /*estilos modal - examenes configuracion*/
    .modal-content > .ibox-content{
        padding: 1rem .75rem;
        padding-top: 0;
    }
</style>
@endpush

@push('scripts')
<script src=" {{ asset('inspinia_admin/js/plugins/dataTables/datatables.min.js') }} "></script>
@endpush


@section('content')
<div>
    @livewire('evaluacion.evaluaciones')
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-7">
                    {{-- @livewire('mantenimiento.ciclos-aulas.aulas') --}}
                </div>
                <div class="col-lg-5">
                   {{--  @livewire('mantenimiento.ciclos-aulas.niveles') --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
