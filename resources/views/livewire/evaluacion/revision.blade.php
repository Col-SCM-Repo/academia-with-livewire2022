<div class="ibox">
    <div class="ibox-content" style="padding-right: 0; padding-left: 0; background: #fff;">
        <div class="col-md-10 " style="border-right: 3px solid #d3d3d3; ">
            <div style="display: flex; ">
                <h5 class="text-uppercase" style="flex-grow: 1">Nivel seleccionado: &nbsp; {{ $nivelSeleccionado? $nivelSeleccionado : ' - '  }} </h5>
                <div>
                    @if ( $nivelSeleccionado == 'LIBRES' )
                        <button class="btn btn-xs btn-success" wire:click="onClickAddAluLibre" > <i class="fa fa-plus" aria-hidden="true"></i> Alumno libre </button>
                    @endif
                </div>
            </div>
            <table class="table table-hover table-inverse table-responsive">
                <thead class="thead-inverse">
                    <tr>
                        <th class="text-center text-uppercase" style="width: 5rem;">Codigo</th>
                        <th class="text-center text-uppercase" style="width: 5rem;">Nivel</th>
                        <th class="text-center text-uppercase" style="width: 3rem;">Aula</th>
                        <th class="text-center text-uppercase" >Apellidos</th>
                        <th class="text-center text-uppercase" >Nombres</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if ($estaAgregandoAlumno)
                            <tr >
                                <td style="padding: 0;"  scope="row">
                                    <input type="text" class="form-control" wire:model.defer = "codigo" @error('codigo') style="background: #ffe0e3;" title="{{$message}}" @enderror maxlength="4">
                                </td>
                                <td style="padding: 0;" >
                                    <input type="text" class="form-control" wire:model.defer = "nivel" @error('nivel') style="background: #ffe0e3;" title="{{$message}}" @enderror disabled>
                                </td>
                                <td style="padding: 0;" >
                                    <input type="text" class="form-control" wire:model.defer = "aula" @error('aula') style="background: #ffe0e3;" title="{{$message}}" @enderror disabled>
                                </td>
                                <td style="padding: 0;" >
                                    <input type="text" class="form-control" wire:model.defer = "apellidos" @error('apellidos') style="background: #ffe0e3;" title="{{$message}}" @enderror>
                                </td>
                                <td style="padding: 0;" >
                                    <input type="text" class="form-control" wire:model.defer = "nombres" @error('nombres') style="background: #ffe0e3;" title="{{$message}}" @enderror>
                                </td>
                                <td style="padding: 0; width: 8rem;" class="text-center" >
                                    <button class="btn btn-xs btn-success" type="button" wire:click="onAddAlumnoLibre" title="Agregar alumno libre"> <i class="fa fa-plus" aria-hidden="true"></i>  </button>
                                    <button class="btn btn-xs btn-danger" type="button" wire:click="onCancelAlumnoLibre" title="Cancelar"> <i class="fa fa-times" aria-hidden="true"></i>  </button>
                                </td>
                            </tr>
                        @endif

                        @if ( $listaCodigosEstudiantes && count($listaCodigosEstudiantes)>0 )
                            @foreach ($listaCodigosEstudiantes as $codigoEstudiante)
                                <tr >
                                    <td style="padding-top: 0; padding-bottom: 0;"  scope="row"> {{ $codigoEstudiante->enrollment_code }} </td>
                                    <td style="padding-top: 0; padding-bottom: 0;" > {{ $codigoEstudiante->level }} </td>
                                    <td style="padding-top: 0; padding-bottom: 0;" > {{ $codigoEstudiante->classroom }} </td>
                                    <td style="padding-top: 0; padding-bottom: 0;" > {{ $codigoEstudiante->surname }} </td>
                                    <td style="padding-top: 0; padding-bottom: 0;" > {{ $codigoEstudiante->name }} </td>

                                    @if ( $nivelSeleccionado == 'LIBRES' )
                                        <td style="padding: 0; width: 8rem;" class="text-center" >
                                            <button class="btn btn-xs btn-info" > <i class="fa fa-pencil" aria-hidden="true"></i> </button>
                                            <button class="btn btn-xs btn-warning" > <i class="fa fa-folder" aria-hidden="true"></i> </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center"> <small>No se encontrarón alumnos</small> </td>
                            </tr>
                        @endif

                    </tbody>
            </table>
        </div>
        <div class="col-md-2 form-horizontal ">
            <h5 class="text-uppercase" >Opciones</h5>
            <div class="text-center" style="border-bottom: 3px solid #d3d3d3; padding: 1rem .125rem;">
                <button class="btn btn-xs btn-primary btn-block" wire:click="onClickGenerarCodes" >
                    <i class="fa fa-refresh" aria-hidden="true" ></i> Generar codigos
                </button>
                <button class="btn btn-xs btn-danger btn-block" wire:click="onClickResetearCodes" >
                    <i class="fa fa-trash" aria-hidden="true" ></i> Eliminar codigos
                </button>
            </div>
            <div style="padding: 1rem .125rem;">
                <h5 class="text-uppercase" >Niveles</h5>
                @foreach ($listaNiveles as $nivel)
                    <button class="btn btn-xs btn-success btn-block" wire:click="onClickNivel('{{$nivel->description }}', {{$nivel->id }})" >
                        <i class="fa fa-users" aria-hidden="true"></i> {{ $nivel->description }}
                    </button>
                @endforeach
                <button class="btn btn-xs btn-success btn-block" wire:click="onClickNivel('LIBRES')" >
                    <i class="fa fa-user-secret" aria-hidden="true"></i> ALUMNOS LIBRES
                </button>
            </div>
        </div>
    </div>
</div>

