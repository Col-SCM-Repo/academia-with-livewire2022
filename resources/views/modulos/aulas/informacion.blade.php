@extends('layouts.app')
@php
$slot = ''
@endphp
{{--
    @push('styles')
        <link href="{{ asset('inspinia_admin/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src=" {{ asset('inspinia_admin/js/plugins/dataTables/datatables.min.js') }} "></script>
    @endpush
 --}}

 @livewire('aula.informacion-general', ['aula_id' => $aula_id] )
