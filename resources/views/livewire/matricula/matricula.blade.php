<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5 > Nueva matricula </h5>
                <span class="label label-primary"> {{ $matricula_id ? 'Registrada' : 'Sin registrar' }} </span>
            </div>
            <div class="ibox-tools">
                <button class="btn btn-xs btn-info " style="color: #fff">
                    Pagos  
                </button>
                <button class="btn btn-xs btn-success ">
                    Descargar  
                </button>
            </div>
        </span>
    </div>
    <div class="ibox-content">
        <form class="row" wire:submit.prevent="create">
            <div class="col-lg-7 form-horizontal">
                <div class="form-group">
                    <label class="col-lg-3 control-label">Ciclo/Nivel/Aula:</label>
                    <div class="col-lg-9">
                        <input type="text" title="Ciclo, nivel y aula a matricular"
                            wire:model.defer="formularioMatricula.classroom_id" class="form-control">
                        <x-input-error variable='formularioMatricula.classroom_id'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Tipo de pago:</label>
                    <div class="col-lg-9 " style="display: flex; flex-wrap: wrap">
                        <div class="form-check">
                            <input class="form-check-input d-inlineblock" type="radio" name="tipo_pago" id="rbtnContado"
                                value="cash" wire:model.defer='formularioMatricula.tipo_pago'>
                            <label class="form-check-label" for="rbtnContado">
                                Contado
                            </label>
                        </div>
                        <div class="form-check" style="padding-left: 1rem">
                            <input class="form-check-input d-inlineblock" type="radio" name="tipo_pago" id="rbtnCredito"
                                value="credit" wire:model.defer='formularioMatricula.tipo_pago'>
                            <label class="form-check-label" for="rbtnCredito">
                                Credito
                            </label>
                        </div>
                        <x-input-error variable='formularioMatricula.tipo_pago'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Costo matricula:</label>
                    <div class="col-lg-3">
                        <input type="text" title="Costo de matricula"
                            wire:model.defer="formularioMatricula.costo_matricula" class="form-control">
                        <x-input-error variable='formularioMatricula.costo_matricula'> </x-input-error>
                    </div>
                    <label class="col-lg-3 control-label">Costo de ciclo:</label>
                    <div class="col-lg-3">
                        <input type="text" title="Costo del ciclo" wire:model.defer="formularioMatricula.costo"
                            class="form-control" id="costo-ciclo">
                        <x-input-error variable='formularioMatricula.costo'> </x-input-error>
                    </div>
                </div>
                <div class="form-group pago-credito">
                    <label class="col-lg-3 control-label">Cuotas:</label>
                    <div class="col-lg-3">
                        <input type="number" title="Costo por matricula" wire:model.defer="formularioMatricula.cuotas"
                            class="form-control" id="cuotas-pago">
                        <x-input-error variable='formularioMatricula.cuotas'> </x-input-error>
                    </div>
                    <label class="col-lg-3 control-label">Monto por cuota:</label>
                    <div class="col-lg-3">
                        <input type="text" title="Costo por cada cuota" class="form-control" id="monto-cuota" disabled>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 form-horizontal">
                <div class="form-group">
                    <label class="col-lg-3 control-label ">Tipo</label>
                    <div class="col-lg-9">
                        <select class="form-control" wire:model.defer="formularioMatricula.tipo_matricula"
                            title="Tipo de matricula">
                            <option value=""> Seleccionar tipo </option>
                            <option value="normal">Normal</option>
                            <option value="beca">Beca</option>
                            <option value="semi-beca">Semi-Beca</option>
                        </select>
                        <x-input-error variable='formularioMatricula.tipo_matricula'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label ">Programa:</label>
                    <div class="col-lg-9">
                        <input type="text" title="Nombres completos del alumno." class="form-control "
                            wire:model.defer="formularioMatricula.career_id">
                        <x-input-error variable='formularioMatricula.career_id'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"> Observaciones:</label>
                    <div class="col-lg-9">
                        <textarea rows="4" title="Observaciones de la matricula." class="form-control "
                            wire:model.defer="formularioMatricula.observaciones"></textarea>
                        <x-input-error variable='formularioMatricula.observaciones'> </x-input-error>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right">
                <div class="alert " role="alert">
                    @error($relative_id)
                        <p class="text-danger">Debe registrar al <span class="alert-link">apoderado</span></p>
                    @enderror
                    @error($student_id)
                        <p class="text-danger">Debe registrar al <span class="alert-link">alumno</span></p>
                    @enderror
                </div>
            </div>
            <div class="col-12 text-right">
                <span wire:loading wire:target="create"> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem">
                    <i class="fa fa-save    "></i>
                    Guardar
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
                    Livewire.hook('message.processed', (msg, {fingerprint}) => {
                        if(fingerprint.name == 'matricula.matricula'){
                            $(".pago-credito").fadeOut();

                            if($("#rbtnContado").is(':checked'))  $(".pago-credito").fadeOut();
                            if($("#rbtnCredito").is(':checked'))  $(".pago-credito").fadeIn();
                        }
                    });
                }); 
    </script>

    @push('scripts')
    <script>
        $(document).ready(()=>{
            $(".pago-credito").fadeOut();

            $("#rbtnContado").on('change', ({target})=>{
                if(target.checked) $(".pago-credito").fadeOut()
            });

            $("#rbtnCredito").on('change', ({target})=>{
                if(target.checked) $(".pago-credito").fadeIn()
            }); 

            const calcularMontoCuota = (e)=>{
                    console.log("666666666666");
                const costo_ciclo = $('#costo-ciclo');
                const cuotas_pago = $('#cuotas-pago');
                const monto_cuota = $('#monto-cuota');

                if( costo_ciclo && cuotas_pago && monto_cuota ){
                    console.log("6666");
                    let costo = null, cuotas= null;
                    if(costo_ciclo.val() != null && costo_ciclo.val() != '' && costo_ciclo.val() >= 0 )
                        costo = costo_ciclo.val();
                    if(cuotas_pago.val() != null && cuotas_pago.val() != '' && cuotas_pago.val() > 0 )
                        cuotas = cuotas_pago.val();

                    if(costo && cuotas)
                        monto_cuota.val( parseFloat(costo/cuotas).toFixed(2) )
                    else
                        monto_cuota.val( '' )
                }
            }

            $('#costo-ciclo').on('change', calcularMontoCuota);
            $('#cuotas-pago').on('change', calcularMontoCuota);

            $('#costo-ciclo').on('keyup', calcularMontoCuota);
            $('#cuotas-pago').on('keyup', calcularMontoCuota);

        });


    </script>
    @endpush
</div>