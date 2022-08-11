<div class="ibox">
    <div class="ibox-title">
        <span>
            <h5> {{ $idEstudiante? 'Alumno existente' : 'Nuevo alumno' }} </h5>
        </span>
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
                                    class="form-control text-uppercase">
                                <span class="input-group-btn">
                                    <button type="button" wire:click="buscar_interno"
                                        class="btn btn-outline btn-primary"
                                        title="Buscar en la base de datos interna del colegio">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                            @error('formularioAlumno.dni') <div class="pr-1 text-danger" role="alert"> * {{ $message }}
                            </div> @enderror
                            <span class="help-block m-b-none text-muted"> Presionar el boton de buscar para obtener
                                informacion del alumno </span>
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
                            @error('formularioAlumno.f_nac') <div class="pr-1 text-danger" role="alert"> * {{ $message
                                }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Telefono:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.telefono"
                                placeholder="Ingrese un numero de contacto. " class="form-control text-uppercase">
                            @error('formularioAlumno.telefono') <div class="pr-1 text-danger" role="alert"> * {{
                                $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Distrito</label>
                        <div class="col-lg-10">
                            <input type="text" autocomplete="of" wire:model.defer="formularioAlumno.distrito"
                                id="distrito" placeholder="Distrito de procedencia. "
                                class="text-uppercase form-control">
                            @error('formularioAlumno.distrito') <div class="pr-1 text-danger" role="alert"> * {{
                                $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Dirección</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.direccion"
                                placeholder="Direciòn de procedencia. " class="form-control text-uppercase">
                            @error('formularioAlumno.direccion') <div class="pr-1 text-danger" role="alert"> * {{
                                $message }} </div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">Nombres:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.nombres"
                                placeholder="Nombres completos del alumno." class="form-control text-uppercase">
                            @error('formularioAlumno.nombres') <div class="pr-1 text-danger" role="alert"> * {{ $message
                                }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">A.Paterno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.ap_paterno"
                                placeholder="Apellido paterno del alumno." class="form-control text-uppercase">
                            @error('formularioAlumno.ap_paterno') <div class="pr-1 text-danger" role="alert"> * {{
                                $message }} </div> @enderror
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-2 control-label">A.Materno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioAlumno.ap_materno"
                                placeholder="apellido materno del alumno." class="form-control  text-uppercase">
                            @error('formularioAlumno.ap_materno') <div class="pr-1 text-danger" role="alert"> * {{
                                $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">I.E. Proc.</label>
                        <div class="col-lg-10">
                            <input type="text" autocomplete="of" wire:model.defer="formularioAlumno.Ie_procedencia"
                                placeholder="instituciòn de procedencia" id="Ie_procedencia"
                                class="form-control typeahead text-uppercase" data-provide="typeahead">
                            @error('formularioAlumno.Ie_procedencia') <div class="pr-1 text-danger" role="alert"> * {{
                                $message }} </div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Egreso</label>
                        <div class="col-lg-4">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" autocomplete="of" wire:model.defer="formularioAlumno.anio_egreso"
                                    class="form-control" title="Año de egreso" placeholder="2022" id="datepicker-year">
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
                            @error('formularioAlumno.anio_egreso') <div class="pr-1 text-danger" role="alert"> * El año
                                de egreso es requerido </div> @enderror
                        </div>
                        <div class="col-lg-6">
                            @error('formularioAlumno.sexo') <div class="pr-1 text-danger" role="alert"> * El campo sexo
                                es requerido </div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center  ">
                <span wire:loading wire:target="buscar_interno"> Buscando alumno ...</span>
                <span wire:loading wire:target="update, create"> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> {{ $idEstudiante?
                    'Actualizar': 'Guardar'}} informaciòn del alumno </button>

                @if ($idEstudiante)
                <button class="btn btn-sm btn-success" type="button" style="padding: .75rem 3rem"
                    wire:click="initialState"> Limpiar formulario </button>
                @endif

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