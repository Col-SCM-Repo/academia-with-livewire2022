<div class="ibox">
    <div class="ibox-title" style="display: flex">
        <h5 style="flex-grow: 1">Niveles</h5>
        <span class="label label-warning-light float-right"> {{ count($lista_niveles) }} Niveles</span>
    </div>
    <div class="ibox-content">
        <div class="table-responsive">
            <table id="table-list-niveles" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Nivel</th>
                        <th class="text-center">Costo</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lista_niveles as $nivel)
                    <tr style="{{ $nivel->status == 1 ? 'background: #1ab39466': '' }}">
                        <td>{{$nivel->description}}</td>
                        <td class="text-center">{{$nivel->price? 'S/. '.$nivel->price:'-' }}</td>
                        <td class="text-center">{{$nivel->status == 1 ? 'ACTIVO':'INACTIVO'}}</td>
                        <td class="text-center">
                            <button class="btn btn-sm text-primary" wire:click="modalOpenEdit({{ $nivel->id }})"
                                style="background: transparent; padding: 0; margin: 0; font-weight: 900" type="button">
                                <i class="fa fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>


    <!-- begin: Modal ciclo -->
    <x-modal-form idForm='form-modal-nivel' titulo="EDITAR NIVEL">
        <form class="px-5" wire:submit.prevent="update">
            <div class="form-group row">
                <label class="col-sm-3 control-label text-right">Descripcion</label>
                <div class="col-lg-9">
                    <input type="text" wire:model.defer="formularioNivel.descripcion"
                        class="form-control text-uppercase" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 control-label text-right">Costo</label>
                <div class="col-lg-9">
                    <input type="text" wire:model.defer="formularioNivel.costo" class="form-control text-uppercase"
                        placeholder="Ingrese el costo del ciclo">
                    <x-input-error variable='formularioNivel.costo'> </x-input-error>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 control-label text-right">Fecha inicio</label>
                <div class="col-lg-9">
                    <input type="date" wire:model.defer="formularioNivel.fInicio" class="form-control">
                    <x-input-error variable='formularioNivel.fInicio'> </x-input-error>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 control-label text-right">Fecha fin</label>
                <div class="col-lg-9">
                    <input type="date" wire:model.defer="formularioNivel.fFin" class="form-control">
                    <x-input-error variable='formularioNivel.fFin'> </x-input-error>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 control-label text-right">Estado</label>
                <div class="col-lg-9">
                    <select wire:model.defer="formularioNivel.estado" class="form-control">
                        <option value="1">ACTIVO</option>
                        <option value="0">INACTIVO</option>
                    </select>
                    <x-input-error variable='formularioNivel.estado'> </x-input-error>
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