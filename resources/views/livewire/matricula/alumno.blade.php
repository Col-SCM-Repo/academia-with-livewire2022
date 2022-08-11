<div class="ibox">
    <div class="ibox-title">
            <h5> Formulario alumno </h5>
            <div class="ibox-tools">
                @if ($idEstudiante)
                    <span class="label label-primary pull-right"> Registrado </span>
                @else
                    <span class="label label-warning-light pull-right"> Sin registrar </span>
                @endif
            </div>
    </div>
    <div class="ibox-content">
        <form class="row " wire:submit.prevent="{{ $idEstudiante? 'update' : 'create' }}">
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">DNI</label>
                        <div class="col-lg-10">
                            <div class="input-group ">
                                <input type="text" wire:model.defer="formularioAlumno.dni"
                                    placeholder="Numero de DNI o carnet de extranjeria "
                                    class="form-control ">
                                <span class="input-group-btn">
                                    <button type="button" wire:click="buscar_interno"
                                        class="btn btn-outline btn-primary"
                                        title="Buscar en la base de datos interna del colegio">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                            <x-input-error variable='formularioAlumno.dni'> </x-input-error>
                            <small class="help-block m-b-none text-muted"> Presionar el boton de buscar para obtener
                                informacion del alumno </small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">F Nac.</label>
                        <div class="col-lg-10">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="date" autocomplete="of" wire:model.defer="formularioAlumno.f_nac"
                                    class="form-control" placeholder="01/01/2000" id="f_nacimiento">
                            </div>
                            <x-input-error variable='formularioAlumno.f_nac'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Telefono:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.telefono"
                                title="Ingrese un numero de contacto. " class="form-control ">
                            <x-input-error variable='formularioAlumno.telefono'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Distrito</label>
                        <div class="col-lg-10 ui-widget">
                            <input type="text" wire:model.defer="formularioAlumno.distrito"
                                id="distrito" title="Distrito de procedencia. "
                                class=" form-control" >
                            <x-input-error variable='formularioAlumno.distrito'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Dirección</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.direccion"
                                title="Direciòn de procedencia. " class="form-control ">
                            <x-input-error variable='formularioAlumno.direccion'> </x-input-error>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">Nombres:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.nombres"
                                title="Nombres completos del alumno." class="form-control ">
                            <x-input-error variable='formularioAlumno.nombres'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">A.Paterno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.ap_paterno"
                                title="Apellido paterno del alumno." class="form-control ">
                            <x-input-error variable='formularioAlumno.ap_paterno'> </x-input-error>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-2 control-label">A.Materno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.ap_materno"
                                title="Apellido materno del alumno." class="form-control  ">
                            <x-input-error variable='formularioAlumno.ap_materno'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">I.E. Proc.</label>
                        <div class="col-lg-10">
                            <input type="text" autocomplete="of" wire:model.defer="formularioAlumno.Ie_procedencia"
                                title="instituciòn de procedencia" id="Ie_procedencia"
                                class="form-control " >
                            <x-input-error variable='formularioAlumno.Ie_procedencia'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">  
                        <label class="col-lg-2 control-label">Egreso</label>
                        <div class="col-lg-4">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" autocomplete="of" wire:model.defer="formularioAlumno.anio_egreso"
                                    class="form-control" placeholder="2022" title="Año de egreso" id="datepicker-year">
                            </div>
                        </div>

                        <label class="col-lg-2 control-label">Sexo</label>
                        <div class="col-lg-4">
                            <select wire:model.defer="formularioAlumno.sexo" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="male">Masculino</option>
                                <option value="female">Femenino</option>
                            </select>
                        </div>
                        <br>
                        <div class="col-lg-6">
                            @error('formularioAlumno.anio_egreso') 
                                <small class="pr-1 text-danger" role="alert"> * El año de egreso es requerido </small> 
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            @error('formularioAlumno.sexo') 
                                <small class="pr-1 text-danger" role="alert"> * El campo sexo es requerido </small> 
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right  ">
                <span wire:loading wire:target="buscar_interno"> Buscando alumno ...</span>
                <span wire:loading wire:target="update, create"> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> 
                    <i class="fa fa-save    "></i> {{ $idEstudiante? 'Actualizar': 'Guardar'}}  
                </button>

                @if ($idEstudiante)
                <button class="btn btn-sm btn-success" type="button" style="padding: .75rem 3rem"
                    wire:click="initialState"> Limpiar formulario 
                </button>
                @endif

            </div>
        </form>
        <script>

            
            /* 
            document.addEventListener('ready', ()=>{
                document.getElementsById("distrito").addEventListener('change',  e => {
                    console.log(e.target.value);
                    $set('formularioAlumno.distrito', e.target.value)
                } )
            }) 
            */
        </script>

    </div>
    @push('scripts')
    <script>
        $(document).ready(()=>{
            Livewire.emit('pagina-cargada-getdata');

            Livewire.on( 'data-autocomplete', ({ distritos, instituciones  })=>{
                
                console.log(distritos);
                console.log(instituciones);
                
                $( "#distrito" ).typeahead({
                source: distritos
                });
                
                $( "#Ie_procedencia" ).typeahead({
                source: instituciones
                }); 
            }); 
            
            /* $("#distrito").on("change", e => {
                console.log(e.target.value);
                $set('formularioAlumno.distrito', e.target.value)
            } ) */
            
/* 
            var distrito = document.getElementById('distrito');
            distrito.dispatchEvent(new Event('input'));
            
            var Ie_procedencia = document.getElementById('Ie_procedencia');
            Ie_procedencia.dispatchEvent(new Event('input'));
             */

            /* $( document ).tooltip(); */
        });
    </script>
    @endpush
</div>