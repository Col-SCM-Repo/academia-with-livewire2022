<div  wire:ignore.self>
    @if ($examen_id)
        <style>
            .bg-danger-casillero {
                background: #edbdbd;
            }
        </style>
        <form class="row" wire:submit.prevent=" {{ $numeroCursosAlmacenados>0? 'update' : 'create' }} " >

            <div class="col-sm-6 form-horizontal " style="border-right: 3px solid #e3e3e3;  " >
                <h4 class="text-center text-uppercase"><strong>Cursos disponibles</strong></h4>
                <div class="row">
                    @foreach ($cursosSeleccionados as $index=>$cursoCheck)
                    <div class="col-md-6">
                        <label for="curso_{{$cursoCheck['curso_id']}}" class="col-8 col-sm-10 control-label" title="{{$cursoCheck['curso_nombre']}}"> {{$cursoCheck['curso_nombre_corto']}} </label>
                        <div class="col-4 col-sm-2">
                            <input type="checkbox" class="" id="curso_{{$cursoCheck['curso_id']}}"  wire:model = '{{ "cursosSeleccionados.$index.curso_check" }}'>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-6 ">
                <h4 class="text-center text-uppercase"><strong>Cursos preguntas</strong></h4>
                <table class="table table-striped table-inverse table-responsive">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Curso</th>
                            <th>Puntos</th>
                            <th>N. Preguntas</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if (count($cursosDetalle)>0)
                                @php  $num_preguntas =1;  @endphp
                                @foreach ($cursosDetalle as $index=>$cursoDetalle)
                                    <tr>
                                        <td style="padding-top: 2px; padding-bottom: 2px;" title="Orden">{{$cursoDetalle['orden']}}</td>
                                        <td style="padding-top: 2px; padding-bottom: 2px;">{{$cursoDetalle['nombre_curso']}} <strong><small>({{$num_preguntas}}-{{$num_preguntas + (int) $cursoDetalle['numero_preguntas'] -1 }})</small></strong> </td>
                                        <td style="padding-top: 2px; padding-bottom: 2px;" class="text-center">
                                            <input style=" width: 4rem;" type="number" step="0.1"  wire:model = '{{ "cursosDetalle.$index.puntaje_correcto" }}'
                                            @error("cursosDetalle.$index.puntaje_correcto") class="bg-danger-casillero " title="Debe ser un numero mayor a 0" @enderror>
                                        </td>
                                        <td style="padding-top: 2px; padding-bottom: 2px;" class="text-center" >
                                            <input style=" width: 4.5rem;"  type="number"  wire:model = '{{ "cursosDetalle.$index.numero_preguntas" }}'
                                            @error("cursosDetalle.$index.numero_preguntas") class="bg-danger-casillero " title="Debe ser un numero entero mayor a 0"  @enderror >
                                        </td>
                                        <td style="padding-top: 2px; padding-bottom: 2px;">
                                            <button class="btn btn-xs btn-success" type="button" wire:click="onBtnUp({{$index}})" {{ $index == 0? 'disabled':'' }} >
                                                <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-xs btn-success" type="button" wire:click="onBtnDown({{$index}})" {{ $cursoDetalle['orden']==count($cursosDetalle)? 'disabled':'' }} >
                                                <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @php $num_preguntas += (int)$cursoDetalle['numero_preguntas'] ; @endphp
                                @endforeach
                            @else
                                @php  $num_preguntas = 0;  @endphp
                                <tr>
                                    <td class="text-center" colspan="4">
                                        <span> Aun no se seleccionan cursos</span>
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                </table>
            </div>
            <div class="col-sm-12 mt-3" style="display: flex">
                @php  $activarBtns = count($cursosDetalle)>0;  @endphp

                <div style="flex-grow: 1; justify-content: center">
                    <span style="display: inline-block; margin-top: 1rem"> {{$numeroPreguntasConfiguradas}} preguntas configuradas/ de {{$numPreguntasEstablecidas}} ( puntaje total: {{$puntajeTotal}} )</span>
                </div>
                <div class="text-right">
                    <button class="btn btn-sm btn-success" type="button" title="Generar preguntas de examen en blanco" {{ ($activarBtns && $numeroCursosAlmacenados>0  )? '':'disabled' }} wire:click="generarNotasExamen({{ $examen_id}})" >
                        <i class="fa fa-save    "></i> Generar preguntas examen
                    </button>

                    <button class="btn btn-sm btn-primary" type="submit" {{ $activarBtns? '':'disabled' }}>
                        <i class="fa fa-save    "></i> {{ $numeroCursosAlmacenados>0 ? 'Actualizar':'Guardar' }} cursos de examen
                    </button>
                </div>
            </div>
        </form>
    @else
        <div class="text-center">
            <small> No se encontró examen</small>
        </div>
    @endif
</div>
