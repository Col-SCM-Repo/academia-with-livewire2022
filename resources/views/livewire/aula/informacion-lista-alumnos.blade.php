<div class="ibox">
    <div class="ibox-title" style="display: flex">
        {{-- <h4 style="flex-grow: 1">ALUMNOS MATRICULADOS</h4> --}}
        <div class="ibox-tools"  style="padding-left: 1rem; flex-grow: 1">
            Buscar: <input type="text" wire:model="search" style="min-width: 30rem" placeholder="Ingrese nombres, apellidos o dni " >
        </div>
        <div style="padding-left: 1rem;">
            <a class="btn btn-xs btn-primary" href="{{ route('reporte.lista.alumnos', ['classroom_id'=>$aula_id ]) }}" target="_blank" >
                <i class="fa fa-download" aria-hidden="true"></i> Lista alumnos
            </a>
            <button class="btn btn-xs btn-success" type="button" wire:click="openBtnModalEvaluacionMasivo">
                    <i class="fa fa-download" aria-hidden="true"></i> Resultados examen
            </button>
            <a class="btn btn-xs btn-info" target="_blank" href="{{ route('reporte.evaluacion.cartas', ['classroom_id'=>$aula_id ]) }}">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Cartas
            </a>
        </div>
    </div>
    <div class="ibox-content" style="padding-top: 0">

        <x-input-error variable='aula_id'> </x-input-error>
        <x-input-error variable='search'> </x-input-error>
        <x-input-error variable='listaAlumnos'> </x-input-error>
        <x-input-error variable='listaExamenes'> </x-input-error>
        <x-input-error variable='examenSeleccionadoId'> </x-input-error>
        <x-input-error variable='fechaInicio'> </x-input-error>
        <x-input-error variable='fechaFinal'> </x-input-error>
        <x-input-error variable='tipoGeneracion'> </x-input-error>
        <x-input-error variable='matriculaId'> </x-input-error>
        <x-input-error variable='examenesMarcados'> </x-input-error>
        <x-input-error variable='mostrarTodosLosAlumnos'> </x-input-error>

        <table class="table table-striped table-inverse table-responsive" style="width: 100%">
            <thead class="thead-inverse">
                <tr style="background: #afafaa63">
                    <th style="padding-top: 2px; padding-bottom: 2px;" class="text-center" >CODIGO</th>
                    <th style="padding-top: 2px; padding-bottom: 2px;" >APELLIDOS Y NOMBRES</th>
                    <th style="padding-top: 2px; padding-bottom: 2px;" class="text-center" >DNI</th>
                    <th style="padding-top: 2px; padding-bottom: 2px;" class="text-center" >ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($listaAlumnos as $alumno)
                        <tr >
                            <td style="padding-top: 2px; padding-bottom: 2px;" scope="row" class="text-center" >{{ $alumno['code'] }}</td>
                            <td style="padding-top: 2px; padding-bottom: 2px;"> {{ $alumno['father_lastname'].' '.$alumno['mother_lastname'].', '.$alumno['name']  }} </td>
                            <td style="padding-top: 2px; padding-bottom: 2px;" class="text-center" > {{ $alumno['document_number'] }} </td>
                            <td style="padding-top: 2px; padding-bottom: 2px;" class="text-right">
                                {{-- <button type="button"  class="btn btn-xs text-danger" disabled > <i class="fa fa-eye" aria-hidden="true"></i> Informacion </button> --}}
                                <button type="button"  class="btn btn-xs text-success" wire:click="openBtnModalEvaluacionIndividual({{ $alumno['enrollment_id'] }})" >
                                     <i class="fa fa-eye" aria-hidden="true"></i> Evaluaciones
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
        <div class="text-right">
            <small style="font-size: 1.250rem">Mostrando: {{ count($listaAlumnos ) }} alumnos </small>
        </div>
    </div>

    <!-- begin: Modal evaluacion-individual -->
    <x-modal-form idForm='form-modal-evaluacion-individual' titulo="REPORTE DE EVALUACIONES">
        <h4 class="control-label text-center text-uppercase">Examenes registrados: </h4>
        <form class="px-5 row" wire:submit.prevent="onBtnGenerarReporteIndividual">
            <div class="col-sm-9">
                <div class="">
                    <table class="table table-striped table-hover table-responsive">
                        <thead >
                            <tr>
                                <th style="padding-top: 2px; padding-bottom: 2px;" >COD</th>
                                <th style="padding-top: 2px; padding-bottom: 2px;" >EXAMEN</th>
                                <th style="padding-top: 2px; padding-bottom: 2px;" >FECHA</th>
                                <th style="padding-top: 2px; padding-bottom: 2px;" ></th>
                            </tr>
                            </thead>
                            <tbody>
                                @if ($examenesMarcados && count($examenesMarcados)>0 )
                                    @foreach ($examenesMarcados as $index=>$resumenExamen)
                                        <tr>
                                            <td style="padding-top: 2px; padding-bottom: 2px;" > {{ $resumenExamen['exam_resumen_id'] }} </td>
                                            <td style="padding-top: 2px; padding-bottom: 2px;"  scope="row"> {{ $resumenExamen['nombre'] }} </td>
                                            <td style="padding-top: 2px; padding-bottom: 2px;" > {{ $resumenExamen['fecha'] }} </td>
                                            <td style="padding-top: 2px; padding-bottom: 2px;" >
                                                <input type="checkbox" wire:model.defer = '{{ "examenesMarcados.$index.check" }}'>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <small style="font-size: 1.125rem">No se encontró examenes registrados</small>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                    </table>

                </div>
                <x-input-error variable='formularioAula.nombre'> </x-input-error>
            </div>
            <div class="col-sm-3 text-right ">
                <span wire:loading wire:target="onBtnGenerarReporteIndividual"> Generando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem">
                    Generar
                </button>
            </div>
        </form>
    </x-modal-form>
    <!-- end: Modal ciclo -->

    <!-- begin: Modal evaluacion-masiva -->
        <x-modal-form idForm='form-modal-evaluacion-masiva' titulo="REPORTE DE EVALUACIONES DEL AULA">

            <form class="px-5 form-horizontal" style="padding-left: 1rem;" wire:submit.prevent="onBtngenerarReporteMasivo">

                <div class="form-group" >
                    <label for="" class="col-sm-3 text-right control-label"> GENERAR POR: </label>
                    <div class="col-sm-9">
                        <label for="rbtn-especifico" class="control-label"> EXAMEN</label>
                        <input wire:model="tipoGeneracion" name="tipoGeneracion" value="especifico" type="radio" id="rbtn-especifico" class="form-check-input">
                        &nbsp;&nbsp;
                        <label for="rbtn-fechas" class="control-label"> RANGO DE FECHAS</label>
                        <input wire:model="tipoGeneracion" name="tipoGeneracion" value="fechas" type="radio" id="rbtn-fechas" class="form-check-input" >
                    </div>
                </div>

                @switch($tipoGeneracion)
                    @case( 'especifico' )
                        <div class="form-group">
                            <label for="select-examenSeleccionado" class="col-sm-3 text-right control-label"> Examen: </label>
                            <div class="col-sm-9">
                                <select class="form-control" wire:model.defer="examenSeleccionadoId" id="select-examenSeleccionado">
                                    <option value="">SELECCIONE UN EXAMEN</option>
                                    @if ($listaExamenes)
                                        @foreach ($listaExamenes as $examen)
                                            <option value="{{ $examen['id'] }}"> {{ $examen['name'] }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-input-error variable='examenSeleccionadoId'> </x-input-error>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-8 text-right control-label"> Todos los alumnos: </label>
                            <input type="checkbox" name="" wire:model.defer="mostrarTodosLosAlumnos">
                            <x-input-error variable='examenSeleccionadoId'> </x-input-error>
                        </div>

                        @break
                    @case( 'fechas' )
                        <div class="form-group">
                            <label for="txt-fecha-inicio" class="col-sm-3 text-right control-label"> F. Inicio: </label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" id="txt-fecha-inicio" wire:model.defer="fechaInicio" style="width: 100%">
                                <x-input-error variable='fechaInicio'> </x-input-error>
                            </div>
                            <label for="txt-fecha-fin" class="col-sm-1 text-right control-label"> Fin: </label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control"id="txt-fecha-fin" wire:model.defer="fechaFinal" style="width: 100%">
                                <x-input-error variable='fechaFinal'> </x-input-error>
                            </div>
                        </div>
                        @break
                    @default
                        <div class="form-group text-center p-3">
                        <small>Especifique el tipo de generación</small>
                        </div>
                @endswitch

                <div class="text-right ">
                    <span wire:loading wire:target="onBtngenerarReporteMasivo"> Generando ...</span>
                    <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem">
                        Generar
                    </button>
                </div>

            </form>
        </x-modal-form>
    <!-- end: Modal evaluacion-masiva -->

    @push('scripts')
        <script>
            Livewire.on('abrir-url-blank', (e)=>{
                let a = document.createElement("a");
                a.setAttribute("href", e);
                a.setAttribute("target", '_blank');
                a.click();
                console.log(a);
            });
        </script>
    @endpush

</div>
