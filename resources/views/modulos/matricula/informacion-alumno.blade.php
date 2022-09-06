@extends('layouts.app')

@php
$slot = ''
@endphp

@push('styles')
    <link href="{{ asset('inspinia_admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet">

    <{{-- style>
        .wizard .content {
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
        }

        .typeahead   > li  {
            list-style:none;
            font-size:12px;
        }

    </> --}}
@endpush

@push('scripts')
    <!-- Steps -->
    <script src="{{ asset('inspinia_admin/js/plugins/steps/jquery.steps.min.js') }}"></script>
    <!-- Jquery Validate -->
    <script src="{{ asset('inspinia_admin/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Typehead -->
    <script src="{{ asset('inspinia_admin/js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
  {{--   <!-- Data picker -->
    <script src="{{ asset('inspinia_admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <!-- Data table -->
    <script src=" {{ asset('inspinia_admin/js/plugins/dataTables/datatables.min.js') }} "></script>

 --}}
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
            <strong>Información matricula</strong>
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="row " >
    <div class=" col-md-4  ">
        @livewire('matricula.buscar-lista-alumnos')
    </div>

    <div class=" col-md-8  " style="min-height: 100vh">
        @livewire('matricula.informacion-alumno')
    </div>

</div>

@endsection

@push('scripts')
    {{-- <script>
        $(document).ready(()=>{
            $('#btnResetComponents').on('click', ()=>{
                alert("reseteando componentes");
                Livewire.emit('reset-forms', true);
            })
        })
    </script> --}}
@endpush

