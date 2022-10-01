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
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content row ">


            </div>
        </div>
    </div>
</div>


@endsection
