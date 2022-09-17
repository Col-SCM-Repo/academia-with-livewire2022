<div >
    <form x-data class="row" wire:submit.prevent="{{$matriculaId? 'update' : 'create' }}">
        <div class="col-lg-7 form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Ciclo/Nivel/Aula: </label>
                <div class="col-lg-9">
                    <select wire:model="classroom" class="form-control" >
                        <option value="">Seleccione un ciclo</option>
                        @foreach ($listaClassrooms as $aula) <option value="{{$aula->aula_id}}"> {{ $aula->nombre }} </option> @endforeach
                    </select>
                    @if ($costoCiclo && $vacantesDisponibles && $totalVacantes )
                        <small class="help-block m-b-none text-success">  {{ $vacantesDisponibles }} vacantes disponibles de {{ $totalVacantes }} </small>
                    @endif
                    <x-input-error variable='classroom'> </x-input-error>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"> Observaciones: </label>
                <div class="col-lg-9">
                    <textarea rows="4" title="Observaciones de la matricula." class="form-control " wire:model="observaciones"  {{$classroom? "":"disabled"}}></textarea>
                    <x-input-error variable='observaciones'> </x-input-error>
                </div>
            </div>
        </div>
        <div class="col-lg-5 form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label" >Costo: </label>
                <div class="col-lg-9">
                    <input type="text" title="Costo del ciclo"  class="form-control" id="costo-ciclo" wire:model.defer="costoCicloFinal" disabled>
                    <x-input-error variable='costoCicloFinal'> </x-input-error>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label ">Descuento: </label>
                <div class="col-lg-9">
                    <div style="display: flex">
                        <select class="form-control text-uppercase" style="flex-grow: 1"  title="Tipo de matricula"  {{$classroom? "":"disabled"}} wire:model="descuento">
                            <option value=""> Sin descuento </option>
                            @foreach ($listaDescuentos as $descuento)
                                <option value="{{$descuento->id}}"> {{$descuento->nombre}} </option>
                            @endforeach
                        </select>
                        {{-- <div>
                            <button class="btn btn-xs btn-outline-success text-success" style="margin-top: 0.5rem; border: none; background: transparent;" type="button">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div> --}}
                    </div>
                    <x-input-error variable='formularioMatricula.tipo_matricula'> </x-input-error>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label ">Programa: </label>
                <div class="col-lg-9">
                    <input type="text" autocomplete="off" title="Carrera a postular." class="form-control" wire:model.defer="carrera" wire:change="$set('carrera', $event.target.value)" id="carrera_matricula" {{$classroom? "":"disabled"}}>
                    <x-input-error variable='carrera'> </x-input-error>
                </div>
            </div>
        </div>
        <div class="col-lg-12 " style="display: flex">
            <span wire:loading wire:target="create, update"> Guardando ...</span>
            <div style="flex-grow: 1" class="text-right">
                @if ($matriculaId)
                    @livewire('matricula.partials.matricula-configuracion-pagos', ['matricula_id' => $matriculaId])
                @endif
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem" {{$classroom? "":"disabled"}}>
                    <i class="fa fa-save"></i> {{$matriculaId? 'Actualizar' : 'Registrar' }} matricula
                </button>
            </div>
        </div>
        <div class="col-lg-12  text-right" style="padding-top: 1rem" >
            <small>NOTA: Debe generar las cuotas de pago despues de guardar los cambios de la matricula </small>
        </div>
    </form>

    @push('scripts')
        <script>
            Livewire.on('render-matriculageneral', ()=>{
                console.log('La pagina se renderizo');
                console.log( @js($listaDescuentos) );
            })

            $(document).ready(()=>{
                Livewire.emit('pagina-cargada-matricula');
                Livewire.on( "data-autocomplete-matricula", ({ carreras })=>{
                    $( "#carrera_matricula" ).typeahead({ source: carreras });
                });
            });
        </script>
    @endpush
</div>
