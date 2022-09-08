<div style="display: inline-block">
    <button class="btn btn-xs btn-primary " wire:click="abrirModal">
        <i class="fa fa-money" aria-hidden="true"></i> Becas
    </button>

<!-- begin: Modal apoderado -->
    <x-modal-form-lg idForm='form-modal-becas' titulo="FORMULARIO BECAS">

        @if ($matriculaId)
            <div class="row ">
                <div class="col-sm-7 col-md-6 ">
                    <div class="form-horizontal">
                        <div class="ibox" style="padding: 0;">
                            <div class="ibox-title" style="display: flex; ">
                                <h4>BECAS REGISTRADAS</h4>
                                <div style="flex-grow: 1" class="text-right">
                                    <button type="button" class="btn btn-xs btn-info" wire:click="aplicarBeca">
                                        <i class="fa fa-ticket" aria-hidden="true"></i> Aplicar beca
                                    </button>
                                    <button type="button" class="btn btn-xs btn-success" wire:click="initialState">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Nueva
                                    </button>
                                </div>
                            </div>
                            <div class="ibox-content" style="padding: 0;" >
                                @if ($listaBecasRegistradas)
                                    <table class="table table-sm table-hover table-striped table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th scope="col">DESCRIPCIÓN</th>
                                                <th scope="col">TIPO</th>
                                                <th scope="col">DESCUENTO</th>
                                                <th scope="col">ACCIONES</th>
                                            </tr>
                                        </thead>
                                        @if (count($listaBecasRegistradas)>0)
                                            <tbody>
                                                @foreach ($listaBecasRegistradas as $becaIterador)
                                                    <tr scope="row">
                                                        <td> {{ $becaIterador->typeScholarship->name }}</td>
                                                        <td> {{ $becaIterador->description }}</td>
                                                        <td> {{ $becaIterador->discount }}</td>
                                                        <td>
                                                            <button class="btn btn-xs btn-danger btn-outline-primary border-0 border-none" style="background: transparent;" wire:click="cargarDataBeca( {{$becaIterador->id }} )">
                                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                            </button>
                                                            <button class="btn btn-xs btn-danger btn-outline-danger border-0 border-none" style="background: transparent;" wire:click="eliminarBeca( {{$becaIterador->id }} )">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center"><span> Aun no tiene becas registradas. </span> </td>
                                            </tr>
                                        @endif

                                    </table>
                                @else
                                    <div>
                                        <span class="text-center"> No se encuentra la matricula </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-5 col-md-6" style=" border-left: 3px solid #dfdfdf">
                    <div class="ibox">
                        <div class="ibox-title" style="display: flex">
                            <h4 style="flex-grow: 1">  {{ $idBeca? 'ACTUALIZAR':'NUEVA' }} BECA </h4>
                            <div>
                                @if ($idBeca)
                                    <span class="label label-primary pull-right"> Registrado </span>
                                @else
                                    <span class="label label-warning-light"> Sin registrar </span>
                                @endif
                            </div>
                        </div>
                        <div class="ibox-content" style="padding: 0;">
                            <form class="form-horizontal" wire:submit.prevent="{{ $idBeca? 'editarBeca' : 'agregarBeca' }}" wire:ignore.self >

                                <div class="form-group">
                                    <label class="col-md-2 col-lg-3 control-label text-left text-sm-right">TIPO</label>
                                    <div class="col-md-10 col-lg-9">
                                        <select wire:model="formularioBeca.tipo" class="form-control col-md-10 col-lg-9">
                                            <option value="">SELECCIONE UNA BECA</option>
                                            @foreach ($listaBecasDisponibles as $beca)
                                                <option value="{{$beca['tipo_id']}}" title="{{$beca['descripcion']}}"> {{ $beca['nombre'] }} </option>
                                            @endforeach
                                        </select>
                                        <x-input-error variable='formularioBeca.tipo'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-md-2 col-lg-3 control-label text-left text-sm-right">DESCRIPCIÓN:</label>
                                    <div class="col-md-10 col-lg-9">
                                        <textarea wire:model.defer="formularioBeca.descripcion" class="form-control text-uppercase" rows="3"></textarea>
                                        <x-input-error variable='formularioBeca.descripcion'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-lg-3 control-label text-left text-sm-right">PARAMETRO:</label>
                                    <div class="col-md-4 col-lg-3">
                                        <input class="form-control " wire:model.defer="formularioBeca.valor" type="number" {{ ($formularioBeca['tipo'] && $listaBecasDisponibles[ $formularioBeca['tipo'] ]['edit'])? '' : 'disabled'  }} >
                                        <x-input-error variable='formularioBeca.valor'> </x-input-error>
                                    </div>

                                    <label class="col-md-2 col-lg-2 control-label text-left text-sm-right">DESCUENTO:</label>
                                    <div class="col-md-4 col-lg-4">
                                        <input class="form-control " wire:text="formularioBeca.descuento" type="number" disabled>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span wire:loading wire:target="editarBeca, agregarBeca"> Guardando ...</span>
                                    <button class="btn btn-xs btn-primary" type="submit" >
                                        <i class="fa fa-stack-overflow" aria-hidden="true"></i> {{$idBeca? 'Actualizar': 'Guardar'}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12  row " style="display: flex"  >
                    <div style="flex-grow: 1; padding-left: 1rem" class="text-left text-mutted">
                        Monto evaluado: <strong>S./ {{ $montoEvaluar  }}, </strong>  descuento: <strong>S./ {{ $descuento  }}</strong>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center">
                <span>No se encontró la matricula del alumno</span>
            </div>
        @endif
    </x-modal-form-lg>
    <!-- end: Modal apoderado -->

</div>
