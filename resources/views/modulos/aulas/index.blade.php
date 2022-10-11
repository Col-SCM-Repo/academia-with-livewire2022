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
        <h2>Aulas</h2>
        <ol class="breadcrumb">
            <li>
                <a href="">Home</a>
            </li>
            <li class="active">
                <a>Aulas</a>
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
            @if ($periodo)
                @php
                    $niveles = $periodo->levels->where('status', 1);
                    $numeroNiveles = count($niveles);
                    $numeroColumnas = 6;
                    switch ($numeroNiveles) {
                        case '1': $numeroColumnas = 12; break;
                        case '2': $numeroColumnas = 6; break;
                        case '3': $numeroColumnas = 4; break;
                        case '4': $numeroColumnas = 6; break;
                    }
                @endphp

                @if ( $numeroNiveles >0)
                    @foreach ($niveles as $nivel)
                        <div class="col-sm-{{$numeroColumnas}} " style="border-right: 1px solid #cbc9c9">
                            @livewire('aula.lista-aulas', ['tipo_nivel_id' => $nivel->id] )
                        </div>
                    @endforeach
                @else
                    <div class="text-center" >
                        <small style="font-size: 1.125rem"> No se encontraron niveles activos. </small>
                    </div>
                @endif
            @else
                <div class="text-center" >
                    <small style="font-size: 1.125rem"> No se encontro periodo. </small>
                </div>
            @endif
        </div>
    </div>
@endsection
