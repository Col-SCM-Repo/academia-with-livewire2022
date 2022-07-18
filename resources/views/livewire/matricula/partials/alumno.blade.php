<div class="ibox">
    <div class="ibox-title">
        <span>
            <h5> {{  $id_alumno? 'Datos del alumno' : 'Nuevo alumno' }} </h5>
        </span>
    </div>
    <div class="ibox-content">
        <form class="row " wire:submit.prevent = "{{ $id_alumno? 'update' : 'create' }}" >
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">DNI</label>
                        <div class="col-lg-10">
                            <div class="input-group ">
                                <input type="text" wire:model.defer="dni" placeholder="Ingrese numero de DNI o carnet de extranjeria " class="form-control">
                                <span class="input-group-btn"> 
                                    <button type="button" wire:click = "buscar_interno" class="btn btn-outline btn-primary" title="Buscar en la base de datos interna del colegio">
                                        <i class="fa fa-search" aria-hidden="true"></i> 
                                    </button> 
                                </span> 
                                <span class="input-group-btn"> 
                                    <button type="button" wire:click = "buscar_reniec" class="btn btn-outline btn-warning" title="Buscar en la RENIEC (solo para ciudadanos de nacionalidad Peruana )">
                                        <i class="fa fa-university" aria-hidden="true"></i> 
                                    </button> 
                                </span> 
                            </div>
                            @error('dni') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                            <span class="help-block m-b-none text-muted"> Presionar el boton de buscar para obtener informacion del alumno  </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">F Nac.</label>
                        <div class="col-lg-10">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="date" autocomplete="of" wire:model.defer="f_nac" class="form-control"  placeholder="01/01/2000" id="f_nacimiento">
                            </div>
                            @error('f_nac') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Telefono:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="telefono" placeholder="Ingrese un numero de contacto. " class="form-control"> 
                            @error('telefono') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Distrito</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="distrito" id="distrito" placeholder="Ingrese el distrito de procedencia. " class="form-control">
                            @error('distrito') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Dirección</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="direccion" placeholder="Ingrese la direciòn de procedencia. " class="form-control">
                            @error('direccion') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">Nombres:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="nombres" placeholder="Ingrese los nombres completos del alumno." class="form-control"> 
                            @error('nombres') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">A.Paterno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="ap_paterno" placeholder="Ingrese el apellido paterno del alumno." class="form-control">
                            @error('ap_paterno') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                    </div>
                    
                    <div class="form-group"><label class="col-lg-2 control-label">A.Materno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="ap_materno" placeholder="Ingrese el apellido materno del alumno." class="form-control">
                            @error('ap_materno') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">I.E. Proc.</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="Ie_procedencia" placeholder="Ingresa la instituciòn educativa de procedencia" id="Ie_procedencia" class="form-control typeahead" data-provide="typeahead"> 
                            @error('Ie_procedencia') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Año. egreso</label>
                        <div class="col-lg-3">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" autocomplete="of"  wire:model.defer="anio_egreso"  class="form-control" placeholder="2022" id="datepicker-year">
                            </div>
                            @error('anio_egreso') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>
                        
                        <label class="col-lg-2 control-label">Sexo</label>
                        <div class="col-lg-5">
                            <select wire:model.defer="sexo" class="form-control">
                                <option >--Seleccione--</option>
                                <option value="male">Masculino</option>
                                <option value="female">Femenino</option>
                            </select>
                            @error('sexo') <div class="alert alert-danger" role="alert"> {{ $message }} </div> @enderror
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 text-center  "  > 
                <span wire:loading > Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> {{  $id_alumno? 'Actualizar': 'Guardar'}} informaciòn del alumno </button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            /* 
            $('#f_nacimiento').datepicker({
                        todayBtn: "linked",
                        keyboardNavigation: false,
                        forceParse: false,
                        calendarWeeks: true,
                        autoclose: true
                });
    
            $('#datepicker-year').datepicker({
                format: "yyyy",
                weekStart: 1,
                orientation: "bottom",
                keyboardNavigation: false,
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            }); */
            
        </script>
        
        <script>
            Livewire.on('data-autocomplete', (data)=>{
                console.log(data);
                $('#Ie_procedencia').typeahead( { source: data.lista_ie_procedencia } );
                $('#distrito').typeahead( { source: data.lista_distritos } );
                //$('#Ie_procedencia').typeahead( { source: data.lista_ie_procedencia } );
            })

            $(()=>{ Livewire.emit( 'ya-cargue', 666); })

        </script>

    @endpush
</div>



