<div>
    {{--  Dependencias del componente: [jquery.steps, bootstrap3-typeahead] --}}
    <div class="tabs-nueva-matricula " style="min-height: 85vh">
        <ul class="nav nav-tabs">
            <li class=" active"><a data-toggle="tab" href="#tab-1"> ALUMNO</a></li>
            <li class=" "><a data-toggle="tab" href="#tab-2">APODERADO</a></li>
            <li class=" "><a data-toggle="tab" href="#tab-3">MATRICULA</a></li>
            <li class=" "><a data-toggle="tab" href="#tab-4">PAGOS</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body ">
                    <!-- Begin: contenido alumno -->
                        @livewire('matricula.partials.alumno')
                    <!-- End: contenido alumno -->
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body ">
                    <!-- Begin: contenido apoderado -->
                        @livewire('matricula.partials.apoderado')
                    <!-- End: contenido apoderado -->
                </div>
            </div>
            <div id="tab-3" class="tab-pane">
                <div class="panel-body ">
                    <!-- Begin: contenido matricula -->
                        @livewire('matricula.partials.matricula')
                    <!-- End: contenido matricula -->
                </div>
            </div>
            <div id="tab-4" class="tab-pane">
                <div class="panel-body ">
                <!-- Begin: contenido pagos -->
                        @livewire('matricula.partials.pago')
                <!-- End: contenido pagos -->
                </div>
            </div>
        </div>
    </div>
</div>
