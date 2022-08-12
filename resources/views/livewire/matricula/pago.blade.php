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
                    <a class="btn btn-xs btn-success " style="color: #FFF">
                        Ver historial pagos 
                    </a>
                    <button type="button" class="btn btn-xs btn-danger" wire:click="abrirModalPagos()" >
                        Registrar pago
                    </button>
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
                                                <button class="btn btn-xs btn-info" {{ $cuota->total_pagado? 'disabled':'' }} href="">Editar</button>
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
                                                <button class="btn btn-xs btn-info" {{ $cuota->total_pagado? 'disabled':'' }} href="">Editar</button>
                                                <button class="btn btn-xs btn-info" {{ ($cuota->total_pagado)? '':'disabled' }} href="">Comprobante de pago</button>
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
            @else
                <h5>No se encontraron cuotas de pago. </h5>
            @endif

        </div>


    </div>
    
    <!-- begin: Modal pagos -->
    <x-modal-form idForm='form-modal-pago' titulo="Registrar pago" >
        <form class="px-5 row" wire:submit.prevent="create">
            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label text-right">Monto de cuota</label>
                        <div class="col-sm-5">
                            <input type="text" wire:model.defer="formularioAula.nombre" class="form-control" disabled >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label text-right">Deuda total</label>
                        <div class="col-sm-5">
                            <input type="text" wire:model.defer="formularioAula.nombre" class="form-control" disabled >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label text-right">Monto a pagar</label>
                        <div class="col-sm-5">
                            <input type="text" wire:model.defer="formularioAula.vacantes" class="form-control text-uppercase"
                                placeholder="Monto a pagar">
                            <x-input-error variable='formularioAula.vacantes'> </x-input-error>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-sm btn-danger" type="submit" style="padding: .75rem 3rem">
                        Pagar
                    </button>
                    <span wire:loading wire:target=" update "> Guardando ...</span>
                </div>
            </div>
        </form>
    </x-modal-form>
    <!-- end: Modal pagos -->

    @push('scripts')
    <script>

    </script>
    @endpush
</div>