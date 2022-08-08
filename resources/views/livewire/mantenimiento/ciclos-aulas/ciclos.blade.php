<div>
    <div class="row  border-bottom white-bg dashboard-header">
        <div class="col-md-3">
            <h2>CICLOS</h2>
            <div class="m-t m-b">

                <p>
                    Ciclo seleccionado:
                    <strong>
                        @if ( Session::has('periodo') )
                        {{ Session::get('periodo')->name }} ( {{ Session::get('periodo')->year }} )
                        @endif
                    </strong>

                </p>
            </div>
            <button type="button" class="btn btn-sm btn-primary" wire:click='nuevoPeriodoModal'>
                <i class="fa fa-plus" aria-hidden="true"></i> Nuevo ciclo
            </button>

            {{-- <div style="margin-top: 2rem">
                <small>
                    El ciclo actual es el ultimo ciclo registrado y vigente,
                    de lo contrario sera el ultimo ciclo creado
                </small>
            </div> --}}
        </div>
        <div class="col-md-9">
            <div>
                <div style="display: flex">
                    <h4 style="flex-grow: 1"> Historial de ciclos </h4>
                    <p>
                        Ciclo :
                        <select wire:model='idCicloSesion' wire:change='cambiarCiclo'>
                            @foreach ($lista_ciclos as $ciclo)
                            <option value="{{$ciclo->id}}">
                                {{ $ciclo->name }}
                            </option>
                            @endforeach
                        </select>
                    </p>
                </div>
                <div>
                    <div class="table-responsive">
                        <table id="table-list-ciclos" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Anio</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lista_ciclos as $ciclo)
                                <tr @if (Session::has('periodo'))
                                    style="{{ Session::get('periodo')->id == $ciclo->id? 'background: #1ab39466': '' }}"
                                    @endif>
                                    <td class="text-center">{{ $ciclo->id }} </td>
                                    <td class="text-center">{{ $ciclo->name }} </td>
                                    <td class="text-center">{{ $ciclo->year }} </td>
                                    <td class="text-center">{{ $ciclo->active == 1? 'ACTIVO' : 'INACTIVO' }} </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm text-primary"
                                            style="background: transparent; padding: 0; margin: 0; font-weight: 900"
                                            type="button" wire:click="selectedPeriodModal({{$ciclo->id}})">
                                            <i class="fa fa-edit"></i> Editar
                                        </button>
                                        &nbsp;
                                        &nbsp;
                                        <button class="btn btn-sm text-danger btn-ciclo-delete"
                                            style="background: transparent; padding: 0; margin: 0; font-weight: 900"
                                            type="button" data-target="{{$ciclo->id}}"
                                            data-targetName="{{$ciclo->name}}">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- begin: Modal ciclo -->
    <x-modal-form idForm='form-modal-ciclo' :titulo="$idCicloEdicion? 'Actualizacion del ciclo' : 'Nuevo Ciclo' ">
        <form class="px-5" wire:submit.prevent="{{ $idCicloEdicion? 'update' : 'create' }}">
            <div class="form-group row">
                <label class="col-sm-2 control-label">Nombre</label>
                <div class="col-lg-10">
                    <input type="text" wire:model.defer="formularioCiclo.nombre" class="form-control text-uppercase"
                        placeholder="Ingrese un nombre para el ciclo. ">
                    <x-input-error variable='formularioCiclo.nombre'> </x-input-error>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 control-label">Año</label>
                <div class="col-lg-10">
                    <input type="text" wire:model.defer="formularioCiclo.anio" class="form-control text-uppercase"
                        placeholder="Ingrese un año para el ciclo">
                    <x-input-error variable='formularioCiclo.anio'> </x-input-error>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 control-label">Estado</label>
                <div class=" col-lg-10">
                    <select wire:model.defer="formularioCiclo.estado" class="form-control">
                        <option value="1">ACTIVO</option>
                        <option value="0">INACTIVO</option>
                    </select>
                    <x-input-error variable='formularioCiclo.estado'></x-input-error>
                </div>
            </div>
            <div class=" text-right ">
                <span wire:loading wire:target=" update, create"> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem">
                    {{$idCicloEdicion? 'Actualizar': 'Guardar'}} ciclo
                </button>
            </div>
        </form>
    </x-modal-form>
    <!-- end: Modal ciclo -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('component.initialized', ({fingerprint}) => {
                console.log(fingerprint.name);
                if(fingerprint.name == 'mantenimiento.ciclos-aulas.ciclos'){
                    $('#table-list-ciclos').DataTable({
                        "order":[[ 2, 'DESC' ]],
                        "pageLength": 10,
                        "responsive": true,
                        "language":{
                            "lengthMenu" : "Mostrar _MENU_ registros",
                            "zeroRecords" : "No se encontraron resultados",
                            "info" : "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            //"info" : "",
                            "infoEmpty" : "Mostrando registros del 0 al 0 de un total de 0 registros",
                            //"infoEmpty" : "",
                            "infoFiltered" : "(Filtrando un total de _MAX_ registros)",
                            "sSearch" : "Buscar:",
                            "oPaginate" : {
                                "sFirst" : "Primero",
                                "sLast" : "Ultimo",
                                "sNext" : "Siguiente",
                                "sPrevious" : "Anterior",

                            },
                            "sProcessing" : "Procesando...",
                        }
                    }); 
                }

            }) 
        
            Livewire.hook('message.processed', (msg, {fingerprint}) => {
                if(fingerprint.name == 'mantenimiento.ciclos-aulas.ciclos'){
                [...document.getElementsByClassName('btn-ciclo-delete')].forEach((el)=>{
                    el.addEventListener('click', ({target})=>{
                        if( target.dataset.targetname ){
                            swal({
                            title: "Estas Seguro?",
                            text: "Se eliminara el ciclo"+target.dataset.targetname,
                            icon: "warning",
                            buttons: true,
                            buttons: ["Cancelar", "Eliminar"],
                            dangerMode: true,
                            })
                            .then((willDelete) => {
                            if (willDelete) {
                                Livewire.emit('eliminar-ciclo', target.dataset.target)
                                swal("Poof! Your imaginary file has been deleted!", {
                                icon: "success",
                                });
                            } 
                        });
                        }

                    })
                })
                }
            }) 
        }); 
    </script>

    @push('scripts')
    <script>
        [...document.getElementsByClassName('btn-ciclo-delete')].forEach((el)=>{
                    el.addEventListener('click', ({target})=>{
                        if( target.dataset.targetname ){
                            swal({
                            title: "Estas Seguro?",
                            text: "Se eliminara el ciclo"+target.dataset.targetname,
                            icon: "warning",
                            buttons: true,
                            buttons: ["Cancelar", "Eliminar"],
                            dangerMode: true,
                            })
                            .then((willDelete) => {
                                if (willDelete) {
                                    Livewire.emit('eliminar-ciclo', target.dataset.target)
                                    swal("Poof! Your imaginary file has been deleted!", {
                                    icon: "success",
                                    });
                                } 
                        });
                        }

                    })
                })




    </script>
    @endpush
</div>