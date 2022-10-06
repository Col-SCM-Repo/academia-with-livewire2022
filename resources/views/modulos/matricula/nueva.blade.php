@extends('layouts.app')

@php $slot = '' @endphp

@push('styles')
    <link href="{{ asset('inspinia_admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet">

    <style>
        .typeahead   > li  {
            list-style:none;
            font-size:12px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('inspinia_admin/js/plugins/steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin/js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
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
    <button class="btn btn-sm btn-success din" type="button" id="btnResetComponents" >
        Limpiar formulario
    </button>
</div>

@endsection

@section('content')
    <div class="row" >
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content" style="padding: 0">
                    @livewire('matricula.nueva-matricula-steps')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(()=>{
            console.log('asdvajsvdj');
            $('#btnResetComponents').on('click', ()=>{
                Livewire.emitTo('matricula.nueva-matricula-steps', 'reset-matricula-steps');
            })
        })
    </script>
@endpush

