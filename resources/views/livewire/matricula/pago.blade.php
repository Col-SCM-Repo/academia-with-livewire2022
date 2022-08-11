<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5 > Cuotas de pago </h5>
                <span class="label label-primary"> 
                    {{ $matricula_id ? 'Con deuda' : 'Sin deuda' }} 
                </span>
            </div>
            <div class="ibox-tools">
                <a class="btn btn-xs btn-success " style="color: #FFF">
                    Ver historial pagos 
                </a>
                <button type="button" class="btn btn-xs btn-danger" wire:click="abrirModalPagos()" >
                    Registrar pago
                </button>
            </div>
        </span>
    </div>
    <div class="ibox-content">

        <div class="px-3">
            <div class="row">
                <label class="col-md-2 col-sm-3" for=""> Cuota matricula </label>
                <div class="col-md-10">
                    <table class="table table-sm table-responsive table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</t>
                                <th scope="col">Estado</t>
                                <th scope="col">Monto cuota</t>
                                <th scope="col">Monto pagado</t>
                                <th scope="col">Historial</t>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row">1</td>
                                <td scope="row">DEUDA </td>
                                <td scope="row">300</td>
                                <td scope="row">200</td>
                                <td scope="row"> 
                                    <a class="btn btn-xs btn-info" href="">Editar</a>
                                    <a class="btn btn-xs btn-info" href="">Comprobante de pago</a>
                                </td>
                            </tr>
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
                                <th scope="col">#</t>
                                <th scope="col">Estado</t>
                                <th scope="col">Monto cuota</t>
                                <th scope="col">Monto pagado</t>
                                <th scope="col">Historial</t>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row">1</td>
                                <td>DEUDA </td>
                                <td>300</td>
                                <td>200</td>
                                <td> 
                                    <a class="btn btn-xs btn-info" href="">Editar</a>
                                    <a class="btn btn-xs btn-info" href="">Comprobante de pago</a> 
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">1</td>
                                <td>DEUDA </td>
                                <td>300</td>
                                <td>200</td>
                                <td> 
                                    <a class="btn btn-xs btn-info" href="">Editar</a>
                                    <a class="btn btn-xs btn-info" href="">Comprobante de pago</a> 
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">1</td>
                                <td>DEUDA </td>
                                <td>300</td>
                                <td>200</td>
                                <td> 
                                    <a class="btn btn-xs btn-info" href="">Editar</a>
                                    <a class="btn btn-xs btn-info" href="">Comprobante de pago</a> 
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">1</td>
                                <td>DEUDA </td>
                                <td>300</td>
                                <td>200</td>
                                <td> 
                                    <a class="btn btn-xs btn-info" href="">Editar</a>
                                    <a class="btn btn-xs btn-info" href="">Comprobante de pago</a> 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

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