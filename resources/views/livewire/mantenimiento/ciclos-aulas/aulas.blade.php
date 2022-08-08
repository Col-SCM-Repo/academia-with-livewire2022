<div class="ibox">
    <div class="ibox-title">
        <div class="ibox-title">
            <h5>Aulas</h5>
        </div>
    </div>
    <div class="ibox-content">
        <ul class="list-group">
            @if (count ($listaNivelesActivos)>0)
            @foreach ($listaNivelesActivos as $nivelActivo)
            <li class="list-group-item " style=" padding-bottom: 3rem">
                <div style="display: flex;">
                    <p class="text-primary" style="flex-grow: 1">
                        <span class="label label-primary"><i class="fa fa-university" aria-hidden="true"></i></span>
                        &nbsp;
                            {{ $nivelActivo->nivel_nombre }}
                    </p>
                    <button class="btn btn-sm text-success"
                        style="background: transparent; padding: 0; margin: 0; border: none"
                        wire:click="openModalNuevaAula({{ $nivelActivo->nivel_id }})">
                        <i class="fa fa-plus" aria-hidden="true"></i> Nuevo
                    </button>
                </div>
                <div>
                    <div class="row text-left">
                        @if (count($nivelActivo->aulas) > 0)
                            @foreach ($nivelActivo->aulas as $aula)
                                <div class="col-xs-3 d-flex" style="padding: 0; padding-left:1rem" >
                                    <div class="btn btn-sm btn-outline btn-primary dim " style="width: 100%" wire:click="openModalEditaAula({{ $aula->id }})">
                                        <button class="btn btn-sm text-danger btn-aula-delete" data-target='{{ $aula->id }}' data-targetName='{{ $aula->nombre }}'
                                            style="padding: 0; margin: 0; background: transparent; font-size:1.25rem; font-weight: 900; float: right; line-height: 1rem" >
                                            X
                                        </button>
                                        <span class="font-bold block" style="font-size: 1.25rem">Aula: {{ $aula->nombre }} </span>
                                        <small class="  block"> {{ $aula->vacantes }} Vacantes</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h5 style="padding-left:1rem">Aun no se encuentran aulas registradas</h5>
                        @endif
                    </div>
                </div>

            </li>
            @endforeach
            @else
            <h3>No se encontrò niveles activos</h3>
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

    <script>

document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (msg, {fingerprint}) => {
                if(fingerprint.name == 'mantenimiento.ciclos-aulas.aulas'){
                    [...document.getElementsByClassName('btn-aula-delete')].forEach((el)=>{
                        console.log(el);
                        el.addEventListener('click', (event)=>{
                            event.stopPropagation();
                            const target = event.target;
                            if( target.dataset.targetname ){
                                swal({
                                title: "Estas Seguro?",
                                text: "Se eliminara el aula "+target.dataset.targetname,
                                icon: "warning",
                                buttons: true,
                                buttons: ["Cancelar", "Eliminar"],
                                dangerMode: true,
                                })
                                .then((willDelete) => {
                                if (willDelete) {
                                    Livewire.emit('eliminar-aula', target.dataset.target)
                                } 
                            });
                            }

                    })
                })
                }
            });
        }); 

    </script>

    @push('scripts')
    <script>
[...document.getElementsByClassName('btn-aula-delete')].forEach((el)=>{
                        console.log(el);
                        el.addEventListener('click', (event)=>{
                            event.stopPropagation();
                            const target = event.target;
                            if( target.dataset.targetname ){
                                swal({
                                title: "Estas Seguro?",
                                text: "Se eliminara el aula "+target.dataset.targetname,
                                icon: "warning",
                                buttons: true,
                                buttons: ["Cancelar", "Eliminar"],
                                dangerMode: true,
                                })
                                .then((willDelete) => {
                                if (willDelete) {
                                    Livewire.emit('eliminar-aula', target.dataset.target)
                                } 
                            });
                            }

                    })
                })
    </script>
    @endpush
</div>