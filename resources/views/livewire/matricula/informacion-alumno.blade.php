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
                                <div class="header-info" style="display: flex;">
                                <h5>Matriculas registradas</h5>
                                <div style="flex-grow: 1;" class="text-right">
                                    <button disabled>
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        Nuevo
                                    </button>
                                </div>
                            </div>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" >Cod.</th>
                                        <th scope="col" >Matricula</th>
                                        <th scope="col" >Fecha</th>
                                        <th scope="col" >Estado</th>
                                    </tr>
                                </thead>
                                <tbody style="cursor: pointer;">
                                    <tr>
                                        <td scope="row">0001</td>
                                        <td>2022-I</td>
                                        <td>20-12-2022</td>
                                        <td>
                                            <button class="btn btn-xs btn-success" title="Ver matricula"> <i class="fa fa-eye" aria-hidden="true"></i> </button>
                                            <button class="btn btn-xs btn-warning" title="Editar matricula" disabled> <i class="fa fa-pencil-square" aria-hidden="true"></i> </button>
                                            <button class="btn btn-xs btn-primary" title="Pagos"> <i class="fa fa-money" aria-hidden="true"></i> </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">0002</td>
                                        <td>2022-II</td>
                                        <td>30-12-2022</td>
                                        <td>
                                            <button class="btn btn-xs btn-success" title="Ver matricula"> <i class="fa fa-eye" aria-hidden="true"></i> </button>
                                            <button class="btn btn-xs btn-warning" title="Editar matricula" disabled> <i class="fa fa-pencil-square" aria-hidden="true"></i> </button>
                                            <button class="btn btn-xs btn-primary" title="Pagos"> <i class="fa fa-money" aria-hidden="true"></i> </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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

    <!-- Modales: edicion de la informacion del alumno -->
    <div class="modals">


    </div>


</div>
