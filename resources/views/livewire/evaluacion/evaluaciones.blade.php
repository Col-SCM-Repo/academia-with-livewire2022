<div>
    <div class="row  border-bottom white-bg dashboard-header">
        <div class="col-md-3">
            <h2>EXAMENES</h2>
            <div class="m-t m-b">

                <p>
                    Examenes para el ciclo:
                    <strong>
                        @if ( Session::has('periodo') )
                            {{ Session::get('periodo')->name }}
                        @endif
                    </strong>
                </p>
            </div>
            <button type="button" class="btn btn-sm btn-primary" wire:click='openModalExamenes'>
                <i class="fa fa-plus" aria-hidden="true"></i> Nuevo examen
            </button>
        </div>

        <div class="col-md-9">
            <div>
                <div style="display: flex">
                    <h4 style="flex-grow: 1"> Examenes creados </h4>
                    <p>
                        <div>
                            @livewire('common.ciclo-select')
                        </div>
                    </p>
                </div>
                <div>
                    <div class="table-responsive">
                        <table id="table-list-ciclos" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- begin: Modal ciclo -->
    <x-modal-form-lg idForm='form-modal-examenes' :titulo="$examen_id? 'Configuraciòn de examen': 'Nuevo examen' ">
        <div class="tabs-modal-examenes">
            <ul class="nav nav-tabs">
                <li class=" active"><a data-toggle="tab" href="#tab-1"> Informacion basica</a></li>
                <li class=" "><a data-toggle="tab" href="#tab-2">Secciones</a></li>
                <li class=" "><a data-toggle="tab" href="#tab-3">Respuestas</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body ">
                        @livewire('evaluacion.partials.configuracion-basica')
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body ">
                        @livewire('evaluacion.partials.configuracion-cursos')
                    </div>
                </div>
                <div id="tab-3" class="tab-pane">
                    <div class="panel-body ">
                        @livewire('evaluacion.partials.configuracion-respuestas')
                    </div>
                </div>
            </div>
        </div>
    </x-modal-form-lg>
    <!-- end: Modal ciclo -->
    <script>

    </script>

    @push('scripts')
    <script>
        /*  */
    </script>
    @endpush
</div>
