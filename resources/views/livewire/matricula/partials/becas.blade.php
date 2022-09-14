<div style="display: inline-block">
    <button class="btn btn-xs btn-primary " wire:click="abrirModal">
        <i class="fa fa-money" aria-hidden="true"></i> Descuentos
    </button>

<!-- begin: Modal apoderado -->
    <x-modal-form-lg idForm='form-modal-becas' titulo="FORMULARIO BECAS">

        <div class="row ">
            <div class="col-sm-7 col-md-6 ">
                <div class="form-horizontal">
                    <div class="ibox" style="padding: 0;">
                        <div class="ibox-title" style="display: flex; ">
                            <h4>BECAS REGISTRADAS</h4>
                            <div style="flex-grow: 1" class="text-right">
                                <button type="button" class="btn btn-xs btn-success" wire:click="initialState">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Nueva
                                </button>
                            </div>
                        </div>
                        <div class="ibox-content" style="padding: 0;" >
                            <table class="table table-sm table-hover table-striped table-bordered table-responsive" {{-- x-data="temp()" --}}>
                                <thead>
                                    <tr>
                                        <th scope="col">COD</th>
                                        <th scope="col">DESCRIPCIÓN</th>
                                        <th scope="col">TIPO</th>
                                        <th scope="col">VALOR DESC.</th>
                                    </tr>
                                </thead>
                                @if ($becas && count($becas)>0)
                                    <tbody>
                                        @foreach ($becas as $beca)
                                            <tr scope="row">
                                                <td style="font-size: 1rem;" > {{ $beca->id }}</td>
                                                <td style="font-size: 1rem;" > {{ $beca->descripcion }}</td>
                                                <td style="font-size: 1rem;" > {{ $beca->tipo }}</td>
                                                <td style="font-size: 1rem;" > {{ $beca->parametro }}</td>
                                                <td class="text-center" style="padding: 0">
                                                    <button class="btn btn-xs text-success" style="background: transparent; border: 0px solid transparent; "  wire:click="descuentoSeleccionado( {{$beca->id }} )">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </button>
                                                    <button class="btn btn-xs text-danger btn-delete-beca" style="background: transparent; border: 0px solid transparent; " onclick="showConfirmacionDescuento({{ $beca->id }})" >
                                                        X
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center"><span> Aun no tiene becas registradas. </span> </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-sm-5 col-md-6" style=" border-left: 3px solid #dfdfdf">
                <div class="ibox">
                    <div class="ibox-title" style="display: flex">
                        <h4 style="flex-grow: 1">  {{ $becaId? 'ACTUALIZAR':'NUEVA' }} BECA </h4>
                        <div>
                            @if ($becaId)
                                <span class="label label-primary pull-right"> Registrado </span>
                            @else
                                <span class="label label-warning-light"> Sin registrar </span>
                            @endif
                        </div>
                    </div>
                    <div class="ibox-content" style="padding: 0;">
                        <form class="form-horizontal" wire:submit.prevent="{{ $becaId? 'update' : 'create' }}"  >

                            <div class="form-group">
                                <label class="col-md-2 col-lg-3 control-label text-left text-sm-right">DESCUENTO</label>
                                <div class="col-md-10 col-lg-9" style="display: flex">
                                    <div style="flex-wrap: 1">
                                        <select wire:model="tipoDescuento" class="form-control">
                                            <option value="">SELECCIONE UN TIPO DESCUENTO</option>
                                            <option value="percentaje">PORCENTUAL</option>
                                            <option value="fixed">MONTO FIJO</option>
                                        </select>
                                        <x-input-error variable='tipoDescuento'> </x-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-md-2 col-lg-3 control-label text-left text-sm-right">DESCRIPCIÓN:</label>
                                <div class="col-md-10 col-lg-9">
                                    <textarea wire:model.defer="descripcion" class="form-control text-uppercase" rows="3"></textarea>
                                    <x-input-error variable='descripcion'> </x-input-error>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-lg-3 control-label text-left text-sm-right">PARAMETRO:</label>
                                <div class="col-md-10 col-lg-9">
                                    <input class="form-control " wire:model="parametroDescuento" type="number" >
                                    <x-input-error variable='parametroDescuento'> </x-input-error>
                                </div>
                            </div>
                            <div class="text-right">
                                <span wire:loading wire:target="editarBeca, agregarBeca"> Guardando ...</span>
                                <button class="btn btn-xs btn-primary" type="submit" >
                                    <i class="fa fa-stack-overflow" aria-hidden="true"></i> {{$becaId? 'Actualizar': 'Guardar'}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-modal-form-lg>
    <!-- end: Modal apoderado -->

    @push('scripts')
    <script>
        /* document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (msg, {fingerprint}) => {
                if(fingerprint.name == 'matricula.partials.becas'){
                    [...document.getElementsByClassName('btn-delete-beca')].forEach((el)=>{
                        el.addEventListener('click', ({target})=>{
                            if( target.dataset.target ){
                                swal({
                                title: "Estas Seguro?",
                                text: "Se eliminara la beca permanentemente",
                                icon: "warning",
                                buttons: true,
                                buttons: ["Cancelar", "Eliminar"],
                                dangerMode: true,
                                }).then((eliminar) => { if (eliminar) Livewire.emit('eliminar-descuento',target.dataset.target) });
                            }
                        })
                    })
                }
            })
        }); */

    </script>

    @endpush

</div>
