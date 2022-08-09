<div>
    @push('styles')

    <link href="{{ asset('inspinia_admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    @endpush
    @push('scripts')
    <!-- Data picker -->
    <script src="{{ asset('inspinia_admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    @endpush

    @section('header')
    <div class="col-md-3">
        <h2>Alumno</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}t">Home</a>
            </li>
            <li>
                <a>Matricula</a>
            </li>
            <li class="active">
                <strong>Alumno</strong>
            </li>
        </ol>
    </div>
    @endsection
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5> Buscar alumnos </h5>
                    <div class="ibox-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".form-alumno">
                            <i class="fa fa-plus" aria-hidden="true"></i> Nuevo alumno
                        </button>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="input-group col-md-8" style=" margin: auto; margin-bottom: 3rem;">
                        <input type="text" class="form-control"
                            placeholder="Ingrese DNI, apellidos o nombres del alumno" wire:model="search">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </span>
                    </div>
                    <div>
                        <p>Resultados de busqueda para {{ $search }} </p>
                        <table class="table table-sm table-responsive table-striped">
                            <thead>
                                <tr>
                                    <th> Nombre </th>
                                    <th> Apellidos </th>
                                    <th> DNI </th>
                                    <th> Acciones </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alumnos as $alumno)
                                <tr>
                                    <td> {{ $alumno->name }} </td>
                                    <td> {{ $alumno->father_lastname.' '.$alumno->mother_lastname }} </td>
                                    <td> {{ $alumno->document_number }} </td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal alumno -->
    <div class="modal fade form-alumno" tabindex=" -1" role="dialog" aria-labelledby="myLargeModalLabel"
        wire:ignore.self aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @livewire('matricula.partials.alumno')
            </div>
        </div>
    </div>
</div>