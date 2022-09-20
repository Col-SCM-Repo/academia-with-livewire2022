<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5 > REGISTRO DE PAGOS </h5>
                @if ($matricula_id)
                    @if ($cuota_matricula && $cuotas_ciclo)
                        @if ( $general_deuda == $general_pagado ) <span class="label label-primary"> Sin deuda </span>
                        @else   <span class="label label-warning-light"> Con deuda </span>  @endif
                    @else
                        <span class="label label-danger"> Sin cuotas </span>
                    @endif
                @endif
            </div>
            @if ($cuota_matricula && $cuotas_ciclo)
                <div class="ibox-tools">
                    <div>
                        <a class="btn btn-xs btn-success " style="color: #FFF"> Ver historial pagos </a>
                    </div>
                    <div>
                        <small class="help-block m-b-none text-primary text-right">
                            Total pagado S./ {{ $general_pagado }} de S./ {{ $general_deuda }} ( S./ {{ $general_deuda-$general_pagado }} pendiente )
                        </small>
                    </div>
                </div>

            @endif
        </span>
    </div>
    <div class="ibox-content">

        <div class="px-3">
            @if ($cuota_matricula && $cuotas_ciclo)
                <div class="row">
                    <label class="col-md-2 col-sm-3" for="">
                        <p>Matricula</p>
                        <button type="button" class="btn btn-xs btn-danger" wire:click='abrirModalPagos("matricula", {{ $cuota_matricula->id  }} )'>
                            <i class="fa fa-money" aria-hidden="true"></i> Pagar
                        </button>
                    </label>
                    <div class="col-md-10">
                        <table class="table table-sm table-responsive table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 1rem">#</th>
                                    <th scope="col" style="width: 20%">Estado</th>
                                    <th scope="col" style="width: 20%">Cuota</th>
                                    <th scope="col" style="width: 20%">Pagado</th>
                                    <th scope="col" style="width: 20%">Fecha limite</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row"> {{ $cuota_matricula->order }} </td>
                                    <td scope="row">
                                        @if (round($cuota_matricula->amount,2) == round($cuota_matricula->abonado(),2) ) <span class=" text-success"> PAGADO </span>
                                        @else <span class=" text-danger"> CON DEUDA </span> @endif
                                    </td>
                                    <td scope="row"> S./ {{ round($cuota_matricula->amount, 2) }} </td>
                                    <td scope="row"> S./ {{ round($cuota_matricula->abonado()) }} </td>
                                    <td scope="row"> {{ $cuota_matricula->deadline }} </td>
                                    <td scope="row">
                                        <button class="btn btn-xs btn-success " {{ count($cuota_matricula->payments)>0? '':'disabled' }} wire:click='abrirModalHistorial( {{ $cuota_matricula->id }} )'>
                                            <i class="fa fa-book" aria-hidden="true"></i>  Historial
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-2 col-sm-3" for="">
                        <p>Ciclo</p>
                        <button type="button" class="btn btn-xs btn-danger" wire:click='abrirModalPagos'> <i class="fa fa-money" aria-hidden="true"></i> Abonar </button>
                    </label>
                    <div class="col-md-10">
                        <table class="table table-sm table-responsive table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 1rem">#</th>
                                    <th scope="col" style="width: 20%">Estado</th>
                                    <th scope="col" style="width: 20%">Cuota</th>
                                    <th scope="col" style="width: 20%">Pagado</th>
                                    <th scope="col" style="width: 20%">Fecha limite</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($cuotas_ciclo)>0)
                                    @php
                                        $esPrimeraCuota = true;
                                    @endphp
                                    @foreach ($cuotas_ciclo as $cuota)
                                        <tr>
                                            <td scope="row"> {{ $cuota->order }} </td>
                                            <td scope="row">
                                                @if (round($cuota->amount,2) == round($cuota->abonado(),2) ) <span class=" text-success"> PAGADO </span>
                                                @else <span class=" text-danger"> CON DEUDA </span> @endif
                                            </td>
                                            <td scope="row"> S./ {{ round($cuota->amount,2) }} </td>
                                            <td scope="row"> S./ {{ round($cuota->abonado(),2) }} </td>
                                            <td scope="row"> {{ $cuota->deadline }} </td>
                                            <td scope="row">
                                                <button class="btn btn-xs btn-success " {{ count($cuota->payments)>0? '':'disabled' }} wire:click='abrirModalHistorial( {{ $cuota->id }} )'>
                                                    <i class="fa fa-book" aria-hidden="true"></i>  Historial
                                                </button>
                                            </td>

                                            @php
                                                /* if($esPrimeraCuota)
                                                    if( ! $cuota->total_pagado )
                                                        $esPrimeraCuota = false; */
                                            @endphp
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td scope="row" colspan="5">
                                            <h5>No se encontraron cuotas</h5>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <h5>No se encontraron cuotas de pago. </h5>
            @endif

        </div>


    </div>

    <!-- begin: Modal pagos -->
    <x-modal-form idForm='form-modal-pago' titulo="Registrar pago" >
        <form class="px-5 row" wire:submit.prevent="pagar">
                <div class="col-sm-9">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label text-right">Deuda de cuota</label>
                        <div class="col-sm-8">
                            <input type="text" wire:model.defer="monto_pendiente_cuota" class="form-control" disabled >
                            <x-input-error variable='monto_pendiente_cuota'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label text-right">Modo de pago</label>
                        <div class="col-sm-8">
                            <select wire:model="modo_pago" class="form-control" >
                                <option value="">SELECCIONE UN MODO DE PAGO</option>
                                <option value="cash">EFECTIVO</option>
                                <option value="deposit">DEPOSITO BANCARIO</option>
                            </select>
                            <x-input-error variable='modo_pago'> </x-input-error>
                        </div>
                    </div>

                    <div class="form-group row"  style="{{ $cuota_id != null ? 'display:none':'' }}">
                        <label class="col-sm-4 control-label text-right">Monto a abonar</label>
                        <div class="col-sm-8">
                            <input type="number"  wire:model.defer="monto_pagar" step="0.1" class="form-control" {{$modo_pago!=''? '':'disabled'}}>
                            <x-input-error variable='monto_pagar'> </x-input-error>
                        </div>
                    </div>

                    @if ($modo_pago=='deposit')
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-right">Nombre de banco</label>
                            <div class="col-sm-8">
                                <input type="text" wire:model.defer="nombre_banco" class="form-control"  >
                                <x-input-error variable='nombre_banco'> </x-input-error>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-right">Numero de operación</label>
                            <div class="col-sm-8">
                                <input type="text" wire:model.defer="numero_operacion" class="form-control"  >
                                <x-input-error variable='numero_operacion'> </x-input-error>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-sm-3" >
                    <button class="btn btn-sm btn-danger" type="submit" style="padding: .75rem 3rem"> Pagar </button>
                    <br> <br>
                    <span wire:loading wire:target="update" > Guardando ...</span>
                </div>
        </form>
    </x-modal-form>
    <!-- end: Modal pagos -->

    <!-- begin: Modal historial -->
    <x-modal-form-lg idForm='form-modal-historial' titulo="Historial de pagos" >
        <div class="px-5" style="padding: 0 2rem  !important;">
            @if ($historial)
                <div class="">
                    <h5>Cuota: {{$historial->id }}</h5>
                    <div class="form-group row">
                        <table class="table table-sm table-hover table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col" > # </th>
                                    <th scope="col" > Monto </th>
                                    <th scope="col" > Usuario </th>
                                    <th scope="col" > Fecha </th>
                                    <th scope="col" > Tipo </th>
                                    <th scope="col" > Acciones </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historial->payments as $pago)
                                    @php
                                        $nota = $pago->note;
                                    @endphp
                                <tr>
                                    <th scope="row"> {{ $pago->numeration }} </th>
                                    <td> {{ $pago->amount }} </td>
                                    <td> {{ $pago->user->nombreCompleto() }} </td>
                                    <td> {{ $pago->created_at }} </td>
                                    <td> {{ $nota ? 'NOTA' : 'TICKET' }} </td>
                                    <td>
                                        <button class="btn btn-xs btn-success" title="Ver boleta">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Boleta
                                        </button>
                                        @if ( !$nota )
                                            <button class="btn btn-xs btn-danger btn-pago-anular" title="Anular pago" data-target="{{ $pago->id }}">
                                                <i class="fa fa-history" aria-hidden="true"></i>
                                                Anular
                                            </button>
                                        @endif
                                    </td>
                                </tr>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                <h5>No se encontró el pago</h5>
            @endif
        </div>
    </x-modal-form-lg>
    <!-- end: Modal historial -->

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', ()=>{
            Livewire.hook('message.processed', (msg, {fingerprint}) => {
                    if(fingerprint.name == 'matricula.partials.pago'){
                        [...document.getElementsByClassName('btn-pago-anular')].forEach((el)=>{
                            el.addEventListener('click', ({target})=>{
                                if( target.dataset.target ){
                                    swal({
                                        title: "Estas Seguro?",
                                        text: "Se anulara el pago",
                                        icon: "warning",
                                        buttons: true,
                                        buttons: ["Cancelar", "Eliminar"],
                                        dangerMode: true,
                                    })
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            Livewire.emit('anular-pago', target.dataset.target)
                                        }
                                    });
                                }
                            })
                        })
                    }
            })
        })
    </script>
    @endpush
</div>
