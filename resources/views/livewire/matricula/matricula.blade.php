<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5 > Nueva matricula </h5>
                @if ( $matricula_id )
                    <span class="label label-warning-light"> Sin registrar </span>
                @else
                    <span class="label label-primary"> Registrado </span>
                @endif
            </div>
            <div class="ibox-tools">
                <button class="btn btn-xs btn-success ">
                    Descargar
                </button>
            </div>
        </span>
    </div>
    <div class="ibox-content">
        <form x-data class="row" wire:submit.prevent="create" >
            <div class="col-lg-7 form-horizontal">
                <div class="form-group">

                    <label class="col-lg-3 control-label">Ciclo/Nivel/Aula:</label>
                    <div class="col-lg-9">
                        <select wire:model="formularioMatricula.classroom_id" class="form-control" >
                            <option value="">Seleccione un ciclo</option>
                                @foreach ($lista_classrooms as $aula)
                                    <option value="{{$aula->aula_id}}"> {{ $aula->nombre }} </option>
                                @endforeach
                        </select>
                        @if ($vacantes_total && $vacantes_disponible)
                            <small class="help-block m-b-none text-success">  {{ $vacantes_disponible }} vacantes disponibles de {{ $vacantes_total }} </small>
                        @endif
                        <x-input-error variable='formularioMatricula.classroom_id'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Tipo de pago:</label>
                    <div class="col-lg-9 " style="display: flex; flex-wrap: wrap">
                        <div class="form-check">
                            <input class="form-check-input d-inlineblock" type="radio" name="tipo_pago" id="rbtnContado"
                                value="cash" wire:model='formularioMatricula.tipo_pago'
                                {{ isset($formularioMatricula['classroom_id']) && is_numeric($formularioMatricula['classroom_id'])? '':'disabled' }} >
                            <label class="form-check-label" for="rbtnContado">
                                Contado
                            </label>
                        </div>
                        <div class="form-check" style="padding-left: 1rem">
                            <input class="form-check-input d-inlineblock" type="radio" name="tipo_pago" id="rbtnCredito"
                                value="credit" wire:model='formularioMatricula.tipo_pago'
                                {{ isset($formularioMatricula['classroom_id']) && is_numeric($formularioMatricula['classroom_id'])? '':'disabled' }} >
                            <label class="form-check-label" for="rbtnCredito">
                                Credito
                            </label>
                        </div>
                        <x-input-error variable='formularioMatricula.tipo_pago'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Costo matricula:</label>
                    <div class="col-lg-3">
                        <input type="text" title="Costo de matricula"
                            wire:model="formularioMatricula.costo_matricula" class="form-control" id="costo-matricula"
                            {{ isset($formularioMatricula['classroom_id']) && is_numeric($formularioMatricula['classroom_id'])? '':'disabled' }}  >
                        <x-input-error variable='formularioMatricula.costo_matricula'> </x-input-error>
                    </div>
                    <label class="col-lg-3 control-label" >Costo de ciclo:</label>
                    <div class="col-lg-3">
                        <input type="text" title="Costo del ciclo"  class="form-control" id="costo-ciclo" wire:model.defer="formularioMatricula.costo" disabled>
                        <x-input-error variable='formularioMatricula.costo'> </x-input-error>
                    </div>
                </div>
                <div class="form-group" style="{{ (isset($formularioMatricula['tipo_pago']) && $formularioMatricula['tipo_pago'] == 'credit')? '' : 'display: none;'  }}" >
                    <label class="col-lg-3 control-label">Cuotas:</label>
                    <div class="col-lg-2">
                        <input type="number" title="Costo por matricula" wire:model="formularioMatricula.cuotas"
                            class="form-control" id="cuotas-pago"
                            {{ isset($formularioMatricula['classroom_id']) && is_numeric($formularioMatricula['classroom_id'])? '':'disabled' }} >
                        <x-input-error variable='formularioMatricula.cuotas'> </x-input-error>
                    </div>
                </div>

                @error('formularioMatricula.tipo_matricula')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.classroom_id')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.carrera')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.tipo_pago')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.cuotas')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.lista_Cuotas')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.lista_cuotas')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.lista_cuotas')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.costo_matricula')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.costo')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.observaciones')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror
                @error('formularioMatricula.student_id')
                    <div class="text-danger"> {{ $message }}  </div>
                @enderror


                @if (isset($formularioMatricula['lista_cuotas']) && is_array($formularioMatricula['lista_cuotas'])
                && count($formularioMatricula['lista_cuotas']) > 0 &&  $formularioMatricula['tipo_pago'] == 'credit')
                    <div class="row">
                        <div class="col-lg-offset-3  col-lg-9">
                            @foreach ($formularioMatricula['lista_cuotas'] as $index=> $cuota_detalle)
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="text-right form-control">Cuota {{ $index+1 }} </label>
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="number" class="form-control" {{ $index == (count($formularioMatricula['lista_cuotas'])-1)? 'disabled' : '' }} wire:model = 'formularioMatricula.lista_cuotas.{{$index}}.costo' >
                                        <div>
                                            @error('formularioMatricula.lista_cuotas.'.$index.'.costo')
                                                <small class="pr-1 text-danger" role="alert"> * Costo requerido</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <input type="date" class="form-control" wire:model = 'formularioMatricula.lista_cuotas.{{$index}}.fecha' >
                                        <div>
                                            @error('formularioMatricula.lista_cuotas.'.$index.'.fecha')
                                                <small class="pr-1 text-danger" role="alert"> * Fecha requerida</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
            <div class="col-lg-5 form-horizontal">
                <div class="form-group">
                    <label class="col-lg-3 control-label ">Tipo</label>
                    <div class="col-lg-9">
                        <select class="form-control" wire:model="formularioMatricula.tipo_matricula"
                            title="Tipo de matricula"
                            {{ isset($formularioMatricula['classroom_id']) && is_numeric($formularioMatricula['classroom_id'])? '':'disabled' }} >
                            <option value=""> Seleccionar tipo </option>
                            <option value="normal">Normal</option>
                            <option value="beca">Beca</option>
                            <option value="semi-beca">Semi-Beca</option>
                        </select>
                        <x-input-error variable='formularioMatricula.tipo_matricula'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label ">Programa:</label>
                    <div class="col-lg-9">
                        <input type="text" title="Nombres completos del alumno." class="form-control "
                            wire:model.defer="formularioMatricula.carrera" name="carrera" id="carrera_matricula"
                            {{ isset($formularioMatricula['classroom_id']) && is_numeric($formularioMatricula['classroom_id'])? '':'disabled' }} >
                        <x-input-error variable='formularioMatricula.carrera'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"> Observaciones:</label>
                    <div class="col-lg-9">
                        <textarea rows="4" title="Observaciones de la matricula." class="form-control "
                            wire:model="formularioMatricula.observaciones"
                            {{ isset($formularioMatricula['classroom_id']) && is_numeric($formularioMatricula['classroom_id'])? '':'disabled' }}
                            ></textarea>
                        <x-input-error variable='formularioMatricula.observaciones'> </x-input-error>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right">
                <div class="alert " role="alert">
                    @error('formularioMatricula.student_id')
                        <div class="text-danger" role="alert">
                            Debe registrar al <span class="alert-link">alumno</span>
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-12 text-right">
                <span wire:loading wire:target="create"> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem">
                    <i class="fa fa-save    "></i>
                    Guardar
                </button>
            </div>
        </form>
    </div>

@push('scripts')
<script>
    $(document).ready(()=>{
        Livewire.emit('pagina-cargada-matricula');

        Livewire.on( 'data-autocomplete-matricula', ({ carreras })=>{
            console.log(carreras);
            $( "#carrera_matricula" ).typeahead({
                source: carreras
            });
            $( "#carrera_matricula" ).change( (e) => {
                Livewire.emit('change-prop-enrollment', { name: e.target.name, value: e.target.value })
            } );
        });
    });
</script>
@endpush

</div>
