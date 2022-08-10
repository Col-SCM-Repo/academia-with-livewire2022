<!------------------------------- Begin: matricula ------------------------------->
<div wire:ignore.self>







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

<div class="ibox">
    <div class="ibox-title">
        <span>
            <h5> Nueva matricula </h5>
        </span>
    </div>
    <div class="ibox-content">
        <form class="row " wire:submit.prevent='update' ">
            <div class="col-lg-3">
                <div class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">Tipo</label>
                        <div class="col-lg-10 ui-widget">
                            <input type="text" 
                                id="distrito" title="Distrito de procedencia. "
                                class=" form-control" >
                            <x-input-error variable='formularioAlumno.distrito'> </x-input-error>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="control-label">Tipo pago</label>
                        <div class="">
                            <div>
                                <label class="control-label">Contado</label> <input type="radio" name="tipo_pago"> <br>
                                <label class="control-label">Tipo pago</label> <input type="radio" name="tipo_pago">
                            </div>
                            <x-input-error variable='formularioAlumno.distrito'> </x-input-error>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Numero de cuotas</label>
                        <div class="col-lg-210">
                            <input type="text" class="form-control">
                            <x-input-error variable='formularioAlumno.distrito'> </x-input-error>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">Ciclo/Nivel/Aula:</label>
                        <div class="col-lg-10 ui-widget">
                            <input type="text" 
                                id="distrito" title="Distrito de procedencia. "
                                class=" form-control" >
                            <x-input-error variable='formularioAlumno.distrito'> </x-input-error>
                        </div>
                    </div>
                    

                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="form-horizontal">
                    <div class="form-group"><label class="col-lg-3 control-label">Programa:</label>
                        <div class="col-lg-9">
                            <input type="text" 
                                title="Nombres completos del alumno." class="form-control ">
                            <x-input-error variable='formularioAlumno.nombres'> </x-input-error>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right  ">
                <span wire:loading wire:target="buscar"> Buscando matricula ...</span>
                <span wire:loading wire:target="update, create"> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> 
                    Guardar  
                </button>

{{--                 @if ($idEstudiante)
                <button class="btn btn-sm btn-success" type="button" style="padding: .75rem 3rem"
                    wire:click="initialState"> Limpiar formulario 
                </button>
                @endif --}}

            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            $(document).ready(()=>{
            });
        </script>
    @endpush
</div>






