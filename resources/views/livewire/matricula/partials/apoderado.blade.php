<div class="ibox">
    <div class="ibox-title">
        <span>
            <h5> {{  $idApoderado? 'Datos del apoderado' : 'Nuevo apoderado' }} </h5>
        </span>
    </div>
    <div class="ibox-content">
        <form class="row " wire:submit.prevent = "{{ $idApoderado? 'update' : 'create' }}" >
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">DNI</label>
                        <div class="col-lg-10">
                            <div class="input-group ">
                                <input type="text" wire:model.defer="formularioApoderado.dni" placeholder="Numero de DNI o carnet de extranjeria " class="form-control text-uppercase">
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
                            @error('formularioApoderado.dni') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                            <span class="help-block m-b-none text-muted"> Presionar el boton de buscar para obtener informacion del alumno  </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">F Nac.</label>
                        <div class="col-lg-10">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="date" autocomplete="of" wire:model.defer="formularioApoderado.f_nac" class="form-control"  placeholder="01/01/2000" id="f_nacimiento">
                            </div>
                            @error('formularioApoderado.f_nac') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Telefono:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.telefono" placeholder="Ingrese un numero de contacto. " class="form-control text-uppercase"> 
                            @error('formularioApoderado.telefono') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Distrito</label>
                        <div class="col-lg-10">
                            <input type="text" autocomplete="of" wire:model.defer="formularioApoderado.distrito" id="distrito" placeholder="Distrito de procedencia. " class="text-uppercase form-control">
                            @error('formularioApoderado.distrito') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Dirección</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.direccion" placeholder="Direciòn de procedencia. " class="form-control text-uppercase">
                            @error('formularioApoderado.direccion') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">Nombres:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.nombres" placeholder="Nombres completos del alumno." class="form-control text-uppercase"> 
                            @error('formularioApoderado.nombres') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">A.Paterno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.ap_paterno" placeholder="Apellido paterno del alumno." class="form-control text-uppercase">
                            @error('formularioApoderado.ap_paterno') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                    </div>
                    
                    <div class="form-group"><label class="col-lg-2 control-label">A.Materno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.ap_materno" placeholder="apellido materno del alumno." class="form-control  text-uppercase">
                            @error('formularioApoderado.ap_materno') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Ocupacion.</label>
                        <div class="col-lg-10">
                            <input type="text" autocomplete="of" wire:model.defer="formularioApoderado.ocupacion" placeholder="Ocupacion del apoderado" id="Ie_procedencia" class="form-control typeahead text-uppercase" data-provide="typeahead"> 
                            @error('formularioApoderado.ocupacion') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Parentesco</label>
                        <div class="col-lg-4">
                            <div class="input-group ">
                                <select wire:model.defer="formularioApoderado.parentesco" class="form-control">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            @error('formularioApoderado.anio_egreso') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>
                        
                        <label class="col-lg-2 control-label">Sexo</label>
                        <div class="col-lg-4">
                            <select wire:model.defer="formularioApoderado.sexo" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="male">Masculino</option>
                                <option value="female">Femenino</option>
                            </select>
                            @error('formularioApoderado.sexo') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 text-center  "  > 
                <span wire:loading wire:target="buscar_interno"  >  Buscando alumno ...</span>
                <span wire:loading wire:target="update, create"  > Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> {{  $idApoderado? 'Actualizar': 'Guardar'}} informaciòn del alumno </button>
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
           /*  Livewire.on('data-autocomplete', (data)=>{
                console.log(data);
                $('#Ie_procedencia').typeahead( { source: data.lista_ie_procedencia } )
                .on('typeahead:selected', function(e, item) {
                        console.log(item);
                        $('#Ie_procedencia').val(item.name) ;
                });
                
                $('#distrito').typeahead( { 
                    classNames: {
                        input: 'Typeahead-input',
                        hint: 'Typeahead-hint',
                        selectable: 'Typeahead-selectable'
                    },
                    source: data.lista_distritos
                });
                $('#distrito').bind('typeahead:select', function(ev, item) {
                    console.log('Selection: ' + item);
                    $('#Ie_procedencia').val(item.name) ;
                });


                //$('#Ie_procedencia').typeahead( { source: data.lista_ie_procedencia } );
            })

            $(()=>{ Livewire.emit( 'ya-cargue', 666); }) */

        </script>

    @endpush
</div>



