<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5 > DATOS DE LA MATRICULA
                    @if ($matricula && $estudianteId)
                        @switch($matricula->status)
                            @case(0)
                                <span class="label label-danger pull-right"> {{ $matricula->statusStudent() }} </span></h5>
                            @break
                            @case(1)
                                <span class="label label-primary pull-right"> {{ $matricula->statusStudent() }} </span></h5>
                            @break
                            @case(-1)
                                <span class="label label-warning pull-right"> {{ $matricula->statusStudent() }} </span></h5>
                            @break
                        @endswitch
                    @else
                        <span class="label label-info pull-right"> Alumno sin matricular </span></h5>
                    @endif
            </div>
            <div>
                @if ($matriculaId)
                    <div style="display: inline">
                        @livewire('matricula.partials.becas')
                    </div>
                    <button class="btn btn-xs btn-success ">
                        <i class="fa fa-download" aria-hidden="true"></i> Ver ficha
                    </button>
                    <button class="btn btn-xs btn-danger btn-retirar-matricula" onclick="showConfirmacionMatricula({{$matriculaId}})">
                        <i class="fa fa-download" aria-hidden="true"></i> Retirar alumno
                    </button>
                @endif
            </div>
        </span>
    </div>
    <div class="ibox-content">
        <div style=" display: {{ $estudianteId ? 'block' : 'none' }} ">
            @livewire('matricula.partials.matricula-configuracion-general')
        </div>

        @if (!$estudianteId)
            <div >
                No se encontrò estudiante
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function showConfirmacionMatricula(matricula_id){
                swal({
                        title: "Estas Seguro?",
                        text: "Se retirara la matricula del estudiante",
                        icon: "warning",
                        buttons: true,
                        buttons: ["Cancelar", "Eliminar"],
                        dangerMode: true,
                    }).then((eliminar) => { if (eliminar) Livewire.emit('retirar-matricula',matricula_id) });
            }


            function showConfirmacionDescuento(descuento_id){
                swal({
                        title: "Estas Seguro?",
                        text: "Se eliminara el descuento permanentemente",
                        icon: "warning",
                        buttons: true,
                        buttons: ["Cancelar", "Eliminar"],
                        dangerMode: true,
                        }).then((eliminar) => { if (eliminar) { Livewire.emit('eliminar-descuento', descuento_id); } });
            }
        </script>
    @endpush
</div>
