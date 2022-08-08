<div class="ibox">
    <div class="ibox-title">
        <div class="ibox-title">
            <h5>Aulas</h5>
        </div>
    </div>
    <div class="ibox-content">
        <ul class="list-group">
            @if (count ($listaNivelesActivos)>0)
            @foreach ($listaNivelesActivos as $key=> $nivelActivo)
            <li class="list-group-item " style=" padding-bottom: 3rem">
                <div style="display: flex;">
                    <p class="text-info" style="flex-grow: 1">
                        <span class="label label-primary"><i class="fa fa-university" aria-hidden="true"></i></span>
                        &nbsp;
                        {{ $key }}
                    </p>
                    <button class="btn btn-sm text-success"
                        style="background: transparent; padding: 0; margin: 0; border: none"
                        wire:click="openModalNuevaAula({{ 45}})">
                        <i class="fa fa-plus" aria-hidden="true"></i> Nuevo
                    </button>
                </div>
                <div>

                    <small class="block text-muted">
                        <i class="fa fa-clock-o"></i> 1 minuts ago
                    </small>

                </div>

            </li>
            @endforeach
            @else
            <h3>No se encontro niveles activos</h3>
            @endif

        </ul>
    </div>

    <!-- begin: Modal ciclo -->
    <x-modal-form idForm='form-modal-aula' :titulo="$aulaSeleccionada_id? 'EDITAR AULA' :'NUEVA AULA'">
        <form class="px-5" wire:submit.prevent="{{ $aulaSeleccionada_id? 'update' :'create'  }}">
            <div class="form-group row">
                <label class="col-sm-2 control-label text-right">Nombre</label>
                <div class="col-lg-10">
                    <input type="text" wire:model.defer="formularioAula.nombre" class="form-control text-uppercase"
                        placeholder="Nombre de la aula">
                </div>
                <x-input-error variable='formularioAula.nombre'> </x-input-error>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label text-right">Vacantes</label>
                <div class="col-lg-10">
                    <input type="text" wire:model.defer="formularioAula.vacantes" class="form-control text-uppercase"
                        placeholder="Ingrese numero de vacantes">
                    <x-input-error variable='formularioAula.vacantes'> </x-input-error>
                </div>
            </div>
            <div class=" text-right ">
                <span wire:loading wire:target=" update "> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem">
                    Guardar
                </button>

            </div>
        </form>
    </x-modal-form>
    <!-- end: Modal ciclo -->

    @push('scripts')
    <script>

    </script>
    @endpush
</div>