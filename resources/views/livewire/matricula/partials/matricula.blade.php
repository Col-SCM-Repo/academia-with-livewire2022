<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5 > DATOS DE LA MATRICULA </h5>
            </div>
            <div>
                @if ($estudianteId)
                    {{-- @if ($matriculaId ) --}}
                        <div>
                            @livewire('matricula.partials.becas')
                        </div>
                        <button class="btn btn-xs btn-success ">
                            <i class="fa fa-download" aria-hidden="true"></i> Ver ficha
                        </button>

                        <button class="btn btn-xs btn-danger ">
                            <i class="fa fa-download" aria-hidden="true"></i> Retirar alumno
                        </button>

                        <span class="label label-primary pull-right"> Alumno matriculado </span>

                    {{-- @else
                        <span class="label label-warning-light pull-right"> Sin registrar </span>
                    @endif --}}
                @endif
            </div>
        </span>
    </div>
    <div class="ibox-content">
        @if ($estudianteId)
            <div>
                @livewire('matricula.partials.matricula-configuracion-general')
            </div>
        @else
            <div >
                No se encontrò estudiante
            </div>
        @endif
    </div>
</div>
