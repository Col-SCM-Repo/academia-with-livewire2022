<div wire:ignore.self style="display: inline-block; text-align: left !important">
    @if ($matriculaId)
        <button class="btn btn-sm {{ $matricula_status == 1? 'bg-warning':'' }} {{ $matricula_status == -1 ? 'bg-danger':'' }}  btn-warning" type="button" style="padding: .75rem 3rem" {{ $matricula_status == 0? 'display':'' }} wire:click="abrirModalCuotasPago">
            <i class="fa fa-money" aria-hidden="true"></i> {{ $matricula_status == 1? 'Actualizar':'Crear' }} cuotas de pago
        </button>

        <div>
            <!-- begin: Modal configuracion cuotas de pago -->
                <x-modal-form idForm='form-modal-cuotas-pago' :titulo="$matricula_status == 1? 'ACTUALIZAR CUOTAS DE PAGO' :'GENERAR CUOTAS DE PAGO'">
                    <form  wire:submit.prevent="{{ $matricula_status == 1? 'update' : 'create' }}" style="padding-right: .125rem;">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Costo matricula:</label>
                                <div class="col-md-4">
                                    <input type="text" wire:model.defer="costoMatricula" class="form-control" {{(!$tipoPago | $tipoPago=='cash')? 'disabled':''}}>
                                    <x-input-error variable='costoMatricula'> </x-input-error>
                                </div>
                                <label class="col-md-2 control-label" >Costo ciclo:</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" title="Costo del ciclo." wire:model.defer="costoCiclo" disabled>
                                    <x-input-error variable='costoCiclo'> </x-input-error>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Tipo de pago:</label>
                                <div class="col-md-4">
                                    <select class="form-control" wire:model="tipoPago">
                                        <option value="">SELECCIONE UN TIPO DE PAGO</option>
                                        <option value="cash">CONTADO</option>
                                        <option value="credit">CREDITO</option>
                                    </select>
                                    <x-input-error variable='tipoPago'> </x-input-error>
                                </div>
                                <label class="col-md-2 control-label" >Abonado:</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" wire:model.defer="costoAbonado" disabled>
                                    <x-input-error variable='costoAbonado'> </x-input-error>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Cuotas:</label>
                                <div class="col-md-9">
                                    <input type="number" title="Numero de cuotas" wire:model="numeroCuotas" class="form-control" {{(!$tipoPago | $tipoPago=='cash')? 'disabled':''}}>
                                    <x-input-error variable='numeroCuotas'> </x-input-error>
                                </div>
                            </div>

                            @if ($detalleCuotas && count($detalleCuotas)>0 )
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-9"  style="margin-top: 1rem; border-top: 3px solid #dfdfdf"></div>
                                    @foreach ($detalleCuotas as $key=>$detalleCuota)
                                        <label class="col-md-offset-3 col-md-2 control-label">Cuota {{$key+1}}  :</label>
                                        <div class="col-md-7 row">
                                            <div class="col-md-6">
                                                <input type="number" step="0.1" title="Monto de la cuota" wire:model.defer="detalleCuotas.{{$key}}.costo" class="form-control "
                                                wire:change="calcularMontoCuotas({{ $key+1 }})" {{ $key == count($detalleCuotas)-1 ? 'disabled':'' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="date" title="Fecha limite de pago" wire:model.defer="detalleCuotas.{{$key}}.fecha" class="form-control " >
                                            </div>
                                            <div class="col-md-12" style="padding-bottom: .75rem;">

                                                @error("detalleCuotas.$key.fecha")
                                                <div class="text-right"> <small class="pr-1 text-danger" role="alert">
                                                        * La fecha es requerida para la cuota {{$key+1}}.
                                                </small> </div>
                                                @enderror

                                                @error("detalleCuotas.$key.costo")
                                                <div class="text-right"> <small class="pr-1 text-danger" role="alert">
                                                        * El costo es requerido y no puede ser 0 o negativo.
                                                </small> </div>
                                                @enderror

                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                            @endif

                            <div class="" style="display: flex">
                                <span wire:loading wire:target="update, create"> Guardando ...</span>
                                <div style="flex-grow: 1" class="text-right">
                                    <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> {{ $matricula_status == 1? 'Actualizar': 'Guardar'}} </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </x-modal-form>
            <!-- end: Modal configuracion cuotas de pago -->
        </div>
    @endif
</div>
