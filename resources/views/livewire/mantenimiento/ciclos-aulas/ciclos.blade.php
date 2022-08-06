<div>
    <div class="row  border-bottom white-bg dashboard-header">
        <div class="col-md-3">
            <h2>CICLOS</h2>
            <div class="m-t m-b">
                <small>Ciclo actual vigente: <strong>XXX</strong></small>
            </div>
            <button type="button" class="btn btn-sm btn-primary" id="btn-open-modal-ciclo">
                <i class="fa fa-plus" aria-hidden="true"></i> Nuevo ciclo
            </button>
        </div>
        <div class="col-md-9">
            <div>
                <h4> Historial de ciclos </h4>
                <div class="row text-center">
                    <p>UNA TABLA</p>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Anio</th>
                                <th>Estado</th>
                                <th>ACciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lista_ciclos as $ciclo)
                            <tr>
                                <td>{{ $ciclo->id }} </td>
                                <td>{{ $ciclo->name }} </td>
                                <td>{{ $ciclo->year }} </td>
                                <td>{{ $ciclo->active }} </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" type="button"
                                        wire:click="selectedPeriod({{$ciclo->id}})">
                                        <i class="fas fa-edit    "></i> Editar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="m-t">
                    <small>
                        El ciclo vigente sera el ultimo ciclo que se halla registrado y se encuentre con estado vigente,
                        de lo contrario sera el ultimo ciclo creado
                    </small>
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
                <div class="col-lg-10">
                    <select wire:model.defer="formularioCiclo.activo" class="form-control"">
                        <option value="">-- Seleccione un estado para el ciclo--</option>
                        <option value='0'>INACTIVO</option>
                        <option value='1'>ACTIVO</option>
                    </select>
                    <x-input-error variable='formularioCiclo.activo'> </x-input-error>
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

    @push('scripts')
    <script>
        $('#btn-open-modal-ciclo').on('click', ()=>{
            $("#form-modal-ciclo").modal('show')
        })
    </script>
    @endpush
</div>