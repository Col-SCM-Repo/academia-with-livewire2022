@extends('layouts.app')
@php
$slot = ''
@endphp

@push('styles')
<style>
    #cabecera-pagina {
        display: none;
    }

    .col-lg-12>.wrapper-content {
        padding-top: 0
    }
</style>
@endpush

@section('content')
<div>
    @livewire('mantenimiento.ciclos-aulas.ciclos')
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-7">
                    @livewire('mantenimiento.ciclos-aulas.aulas')
                </div>
                <div class="col-lg-5">
                    @livewire('mantenimiento.ciclos-aulas.niveles')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection