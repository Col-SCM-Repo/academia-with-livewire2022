<div class="ibox">
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5> MATRICULAS REGISTRADAS </h5>
            </div>
            @if ($estudianteId)
                <div class="ibox-tools">
                    <button wire:click="nuevaMatricula"> <i class="fa fa-plus" aria-hidden="true"></i> Nuevo</button>
                </div>
            @endif
        </span>
    </div>
    <div class="ibox-content">
        @if ($listaMatriculas)
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th scope="col" >Codigo</th>
                        <th scope="col" >Matricula</th>
                        <th scope="col" >Fecha</th>
                        <th scope="col" >Estado</th>
                        <th scope="col" >Acciones</th>
                    </tr>
                </thead>
                <tbody style="cursor: pointer;">
                    @if (count($listaMatriculas)>0)
                        @foreach ($listaMatriculas as $matricula)
                            <tr>
                                <td scope="row"> {{ $matricula->matricula_codigo }} </td>
                                <td>{{ $matricula->descripcion }}</td>
                                <td>{{ $matricula->fecha }}</td>
                                <td> {{ $matricula->estado_matricula }} </td>
                                <td>
                                    <button class="btn btn-xs btn-success" wire:click="mostrarInformacionMatricula({{ $matricula->matricula_id }})" title="Ver matricula">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-xs btn-warning" wire:click="mostrarInformacionMatricula({{ $matricula->matricula_id }})" title="Editar matricula" disabled>
                                        <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-xs btn-primary" wire:click="retirarMatricula({{ $matricula->matricula_id }})" title="Pagos">
                                        <i class="fa fa-money" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-xs btn-danger" wire:click="retirarMatricula({{ $matricula->matricula_id }})" title="Eliminar">
                                        <i class="fa fa-money" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-cente"> <span> Sin registros</span> </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @else
            <h5>No se encontro matriculas. </h5>
        @endif
    </div>

        <!-- begin: Modal apoderado -->
        <x-modal-form-lg idForm='form-modal-nueva-matricula' titulo="NUEVA MATRICULA">
            @livewire('matricula.matricula')
        </x-modal-form-lg>
        <!-- end: Modal apoderado -->
</div>
