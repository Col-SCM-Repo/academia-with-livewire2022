<div>
        
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
    @php $slot = ''; @endphp
    @endsection

    @section('content')
    <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5> Buscar alumnos </h5>
                        <div class="ibox-tools">
                            <button class="btn btn-sm btn-primary" type="button" style="margin-left: 5rem">
                                <i class="fa fa-plus" aria-hidden="true"></i> Nuevo alumno
                            </button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="input-group col-md-8" style=" margin: auto; margin-bottom: 3rem;">
                            <input type="text" class="form-control" placeholder="Ingrese DNI, apellidos o nombres del alumno"> 
                            <span class="input-group-btn"> 
                                <button type="button" class="btn btn-primary">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button> 
                            </span>
                        </div>

                        ---- Lista de alumnos ------
                    </div>
                    <br>

                    <!-- Large modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button>

                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            @livewire('matricula.partials.alumno')
                            ...
                        </div>
                    </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endsection
</div>
