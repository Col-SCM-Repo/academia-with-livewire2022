<!------------------------------- Begin: matricula ------------------------------->
<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span>
            <h5> Nueva matricula </h5>
        </span>
    </div>
    <div class="ibox-content">
        <form class="row " wire:submit.prevent="create">
            <div class="col-lg-7">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Ciclo/Nivel/Aula:</label>
                        <div class="col-lg-9">
                            <input type="text" title="Ciclo, nivel y aula a matricular" class="form-control" >
                            <x-input-error variable='formularioAlumno.distrito'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Tipo de pago:</label>
                        <div class="col-lg-5 " style="display: flex; flex-wrap: wrap">
                            <div class="form-check" >
                                <input class="form-check-input d-inlineblock" type="radio" name="tipo_pago" id="rbtnContado" value="cash" checked>
                                <label class="form-check-label" for="rbtnContado">
                                    Contado
                                </label>
                                </div>
                                <div class="form-check" style="padding-left: 1rem">
                                    <input class="form-check-input d-inlineblock" type="radio" name="tipo_pago" id="rbtnCredito" value="credit" checked>
                                    <label class="form-check-label" for="rbtnCredito">
                                    Credito
                                    </label>
                                </div>
                            <x-input-error variable=''> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Costo matricula:</label>
                        <div class="col-lg-3">
                            <input type="text" title="Costo de matricula" wire:model.defer="formularioMatricula.costo_matricula" class="form-control" >
                            <x-input-error variable='formularioMatricula.costo_matricula'> </x-input-error>
                        </div>
                        <label class="col-lg-3 control-label">Costo de ciclo:</label>
                        <div class="col-lg-3">
                            <input type="text" title="Costo del ciclo" wire:mode.defer="formularioMatricula.costo" class="form-control" >
                            <x-input-error variable='formularioMatricula.costo'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Cuotas:</label>
                        <div class="col-lg-3">
                            <input type="number" title="Costo por matricula" wire:model.defer="formularioMatricula.cuotas" class="form-control" >
                            <x-input-error variable='formularioMatricula.cuotas'> </x-input-error>
                        </div>
                        <label class="col-lg-3 control-label">Monto por cuota:</label>
                        <div class="col-lg-3">
                            <input type="text" title="Costo por cada cuota" class="form-control" disabled>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-3 control-label ">Tipo</label>
                        <div class="col-lg-9">
                            <select class="form-control" wire:model.defer = "formularioMatricula.tipo_matricula" title="Tipo de matricula">
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
                            <input type="text" 
                                title="Nombres completos del alumno." class="form-control " wire:model.defer="formularioMatricula.career_id">
                            <x-input-error variable='formularioMatricula.career_id'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label "> Observaciones:</label>
                        <div class="col-lg-9">
                            <textarea rows="4" title="Observaciones de la matricula." class="form-control " wire:model.defer="formularioMatricula.observaciones"></textarea>
                            <x-input-error variable='formularioMatricula.observaciones'> </x-input-error>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="col-12 text-right  ">
                <span wire:loading wire:target="update, create"> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> 
                    Guardar  
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            $(document).ready(()=>{
            });
        </script>
    @endpush






    Una Matricula


    Dtos requridos

    // Table matricula
    * code 
    * type
    * student_id
    * relative_id	
    * classroom_id	
    * relative_relationship	
    * user_id	
    * career_id	
    * payment_type	
    * fees_quantity	
    * period_cost	
    * cancelled	
    * observations	

<br>
    // Cuotas (payments)    al matricularse se indica las cuotas
    * enrollment_id	
    * order	
    * type	
    * amount	
    * state

<br>
    // Pagos (pagos realizados pòr el alumno)
    * installment_id	
    * amount	
    * type	
    * concept_type	
    * user_id	
    * payment_id	
    * serie	
    * numeration

    <br>
    para pagos se tiene en cuenta la tabla secuences
    * se utiliza un numero de serie (revisar) *


</div>
<!------------------------------- End: matricula ------------------------------->

