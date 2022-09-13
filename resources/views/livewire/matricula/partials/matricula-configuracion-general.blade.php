<div >
    <form x-data class="row" wire:submit.prevent="{{$matricula_id? 'update' : 'create' }}">
        <div class="col-lg-7 form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Ciclo/Nivel/Aula:</label>
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
                <label class="col-lg-3 control-label"> Observaciones:</label>
                <div class="col-lg-9">
                    <textarea rows="4" title="Observaciones de la matricula." class="form-control " wire:model="observaciones"  {{$classroom? "":"disabled"}}></textarea>
                    <x-input-error variable='observaciones'> </x-input-error>
                </div>
            </div>
        </div>
        <div class="col-lg-5 form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label" >Costo:</label>
                <div class="col-lg-9">
                    <input type="text" title="Costo del ciclo"  class="form-control" id="costo-ciclo" wire:model.defer="costoCiclo" disabled>
                    <x-input-error variable='costoCiclo'> </x-input-error>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label ">Descuento</label>
                <div class="col-lg-9">
                    <select class="form-control"
                        title="Tipo de matricula"  {{$classroom? "":"disabled"}}>
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
                    <input type="text" autocomplete="off" title="Carrera a postular." class="form-control" wire:model.defer="carrera" wire:change="$set('carrera', $event.target.value)" id="carrera_matricula" {{$classroom? "":"disabled"}}>
                    <x-input-error variable='carrera'> </x-input-error>
                </div>
            </div>
        </div>
        <div class="text-right">
            <span wire:loading wire:target="create, update"> Guardando ...</span>
            @if ($matricula_id)
                <button class="btn btn-sm btn-warning" type="button" style="padding: .75rem 3rem">
                    <i class="fa fa-money" aria-hidden="true"></i> Generar cuotas de pago
                </button>
            @endif
            <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem" {{$classroom? "":"disabled"}}>
                <i class="fa fa-save"></i> {{$matricula_id? 'Actualizar' : 'Registrar' }} matricula
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(()=>{
                Livewire.emit('pagina-cargada-matricula');
                Livewire.on( 'data-autocomplete-matricula', ({ carreras })=>{
                    $( "#carrera_matricula" ).typeahead({ source: carreras });
                });
            });
        </script>
    @endpush
</div>
