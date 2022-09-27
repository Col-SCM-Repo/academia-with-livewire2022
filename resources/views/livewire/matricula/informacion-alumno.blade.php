<div>

    @push('styles')
        <style>
            .nav-tabs>.active{
               /*  background: red !important; */
            }
        </style>
    @endpush

    <div class="ibox float-e-margins ">
        <div class="ibox-content " style="padding: 0 ">

            <div class="tabs-informacion-curso">
                <ul class="nav nav-tabs">
                    <li class=" active"><a data-toggle="tab" href="#tab-1"> Estudiante</a></li>
                    <li class=" "><a data-toggle="tab" href="#tab-2">Apoderados</a></li>
                    <li class=" "><a data-toggle="tab" href="#tab-3">Matricula</a></li>
                    <li class=" "><a data-toggle="tab" href="#tab-4">Pagos</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body ">
                            @livewire('matricula.alumno')
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body ">
                            <!-- Begin: contenido alumno -->
                            @livewire('matricula.apoderado')
                            <!-- End: contenido alumno -->
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body ">
                            <!-- Begin: contenido matricula -->
                            @livewire('matricula.registro-matriculas')
                            <!-- End: contenido matricula -->
                        </div>
                    </div>
                    <div id="tab-4" class="tab-pane">
                        <div class="panel-body ">
                            @livewire('matricula.pago')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
