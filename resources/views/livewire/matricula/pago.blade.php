<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5 > Cuotas de pago </h5>
                    @if ($cuotas)
                        @if ( $cuotas['total_pagado'])
                            <span class="label label-primary"> Sin deuda </span>
                        @else
                            <span class="label label-warning-light"> Con deuda </span>
                        @endif
                        
                    @else
                        <span class="label label-danger"> Sin registrar </span>
                    @endif

            </div>
            @if ($cuotas)
                <div class="ibox-tools">
                    <div>
                        <a class="btn btn-xs btn-success " style="color: #FFF">
                            Ver historial pagos 
                        </a>
                        <button type="button" class="btn btn-xs btn-danger"  >
                            Registrar pago
                        </button>
                    </div>
                    <div>
                        <small class="help-block m-b-none text-primary text-right">  
                            Total pagado S./ {{ $cuotas['monto_deuda_pagado'] }} de S./ {{ $cuotas['monto_deuda_inicial'] }} ( S./ {{ $cuotas['monto_deuda_pendiente'] }} pendiente )  
                        </small>
                    </div>
                </div>
                
            @endif
        </span>
    </div>
    <div class="ibox-content">

        <div class="px-3">
            @if ($cuotas)
                <div class="row">
                    <label class="col-md-2 col-sm-3" for=""> Cuota matricula </label>
                    <div class="col-md-10">
                        <table class="table table-sm table-responsive table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 1rem">#</t>
                                    <th scope="col" style="width: 20%">Estado</t>
                                    <th scope="col" style="width: 20%">Monto cuota</t>
                                    <th scope="col" style="width: 20%">Monto pagado</t>
                                    <th scope="col">Acciones</t>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($cuotas['matricula'])>0)
                                    @foreach ($cuotas['matricula'] as $cuota)
                                        <tr>
                                            <td scope="row"> {{ $cuota->orden }} </td>
                                            <td scope="row"> 
                                                @if ($cuota->total_pagado)
                                                    <span class=" text-success"> PAGADO </span>
                                                @else
                                                    <span class=" text-danger"> CON DEUDA </span>
                                                @endif 
                                            </td>
                                            <td scope="row"> {{ $cuota->monto_cuota }} </td>
                                            <td scope="row"> {{ $cuota->monto_pagado }} </td>
                                                <td scope="row"> 
                                                <button class="btn btn-xs btn-info" {{ $cuota->total_pagado? 'disabled':'' }} href="" wire:click="abrirModalPagos({{$cuota->id}}, false )" >Editar</button>
                                                <button class="btn btn-xs btn-info" {{ ($cuota->total_pagado && $cuota->monto_cuota != 0 )? '':'disabled' }} href="">Comprobante de pago</button>
                                            </td>
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
                
                <div class="row">
                    <label class="col-md-2 col-sm-3" for=""> Cuotas ciclo </label>
                    <div class="col-md-10">
                        <table class="table table-sm table-responsive table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 1rem">#</t>
                                    <th scope="col" style="width: 20%">Estado</t>
                                    <th scope="col" style="width: 20%">Monto cuota</t>
                                    <th scope="col" style="width: 20%">Monto pagado</t>
                                    <th scope="col">Acciones</t>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($cuotas['ciclo'])>0)
                                    @php
                                        $esPrimeraCuota = true;
                                    @endphp
                                    @foreach ($cuotas['ciclo'] as $cuota)
                                        <tr>
                                            <td scope="row"> {{ $cuota->orden }} </td>
                                            <td scope="row"> 
                                                @if ($cuota->total_pagado)
                                                    <span class=" text-success"> PAGADO </span>
                                                @else
                                                    <span class=" text-danger"> CON DEUDA </span>
                                                @endif 
                                            </td>
                                            <td scope="row"> {{ $cuota->monto_cuota }} </td>
                                            <td scope="row"> {{ $cuota->monto_pagado }} </td>
                                            <td scope="row"> 
                                                <button class="btn btn-xs btn-info" {{ ($esPrimeraCuota && ! $cuota->total_pagado )? '':'disabled' }} wire:click="abrirModalPagos({{$cuota->id}} )" >Editar</button>
                                                <button class="btn btn-xs btn-info" {{ ($cuota->total_pagado)? '':'disabled' }} href="">Comprobante de pago</button>
                                            </td>
                                            
                                            @php
                                                if($esPrimeraCuota)
                                                    if( ! $cuota->total_pagado )
                                                        $esPrimeraCuota = false;
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
                    @if ( $autoFraccionamiento )
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-right">Deuda pendiente</label>
                            <div class="col-sm-8">
                                <input type="text" wire:model.defer="formularioPago.deuda_pendiente" class="form-control" disabled >
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-4 control-label text-right">Costo de cuota</label>
                        <div class="col-sm-8" style="display: flex" x-data="{edition:false}">
                            <input type="number"  wire:model.defer="formularioPago.costo_cuota" class="form-control" :disabled="!edition" >
                            @if ( $autoFraccionamiento )
                                <div>
                                    <button type="button" class="btn btn-sm btn-success " x-on:click="edition=true" x-show="!edition" title="Editar cuota" >
                                        <i class="fa fa-edit "></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary " x-on:click="edition=false" x-show="edition" title="Guardar cuota" >
                                        <i class="fa fa-save "></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-3" >
                    <button class="btn btn-sm btn-danger" type="submit" style="padding: .75rem 3rem"> Pagar </button>
                    <br> <br>
                    <span wire:loading wire:target="update" > Guardando ...</span>
                </div>
        </form>
    </x-modal-form>
    <!-- end: Modal pagos -->

    @push('scripts')
    <script>

    </script>
    @endpush
</div>