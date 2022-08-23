<div class="ibox">
    <div class="ibox-title">
        <h5> Formulario apoderado </h5>
        <div class="ibox-tools">
            @if ($idRelacionApoderado)
                <span class="label label-primary pull-right"> Registrado </span>
            @else
                <span class="label label-warning-light pull-right"> Sin registrar </span>
            @endif
        </div>

    </div>
    <div class="ibox-content">
        <form class="row " wire:submit.prevent = "{{ $idRelacionApoderado? 'update' : 'create' }}" >
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
                            </div>
                            <small class="help-block m-b-none text-muted"> Presionar el boton de buscar para obtener informacion del alumno </small>
                            <x-input-error variable='formularioApoderado.dni'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">F Nac.</label>
                        <div class="col-lg-10">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="date" autocomplete="off " wire:model.defer="formularioApoderado.f_nac" class="form-control"  placeholder="01/01/2000" id="f_nacimiento">
                            </div>
                            <x-input-error variable='formularioApoderado.f_nac'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Telefono:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.telefono" title="Ingrese un numero de contacto. " class="form-control text-uppercase">
                            <x-input-error variable='formularioApoderado.telefono'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Distrito</label>
                        <div class="col-lg-10">
                            <input type="text" autocomplete="off " wire:model.defer="formularioApoderado.distrito" name="distrito" id="distrito_apoderado" title="Distrito de procedencia. " class="text-uppercase form-control">
                            <x-input-error variable='formularioApoderado.distrito'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Dirección</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.direccion" title="Direciòn de procedencia. " class="form-control text-uppercase">
                            <x-input-error variable='formularioApoderado.direccion'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">Parentesco</label>
                        <div class="col-lg-10">
                            <select class="form-control" wire:model.defer="formularioApoderado.parentesco">
                                <option value="">Seleccione</option>
                                <option value="father">Padre</option>
                                <option value="mother">Madre</option>
                                <option value="brother">Hermano</option>
                                <option value="sister">Hermana</option>
                                <option value="uncle">Tio(a)</option>
                                <option value="grandparent">Abuelo(a)</option>
                                <option value="cousin">Primo</option>
                                <option value="other">Otro</option>
                            </select>
                            <x-input-error variable='formularioApoderado.parentesco'> </x-input-error>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">Nombres:</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.nombres" title="Nombres completos del alumno." class="form-control text-uppercase">
                            <x-input-error variable='formularioApoderado.nombres'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">A.Paterno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.ap_paterno" title="Apellido paterno del alumno." class="form-control text-uppercase">
                            <x-input-error variable='formularioApoderado.ap_paterno'> </x-input-error>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-2 control-label">A.Materno</label>
                        <div class="col-lg-10">
                            <input type="text" wire:model.defer="formularioApoderado.ap_materno" title="Apellido materno del alumno." class="form-control  text-uppercase">
                            <x-input-error variable='formularioApoderado.ap_materno'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Ocupacion.</label>
                        <div class="col-lg-10">
                            <input type="text" autocomplete="off " wire:model.defer="formularioApoderado.ocupacion" name="ocupacion" title="Ocupacion del apoderado" id="ocupacion_apoderado" class="form-control typeahead text-uppercase" data-provide="typeahead">
                            <x-input-error variable='formularioApoderado.ocupacion'> </x-input-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">E. Civil</label>
                        <div class="col-lg-4">
                            <div class="input-group ">
                                <select wire:model.defer="formularioApoderado.estado_marital" class="form-control">
                                    <option value="">Seleccione</option>
                                    <option value="single">Soltero(a)</option>
                                    <option value="married">Casado(a)</option>
                                    <option value="divorcied">Divorciado(a)</option>
                                    <option value="widower">Viudo(a)</option>
                            </select>
                            </div>
                        </div>

                        <label class="col-lg-2 control-label">Sexo</label>
                        <div class="col-lg-4">
                            <select wire:model.defer="formularioApoderado.sexo" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="male">Masculino</option>
                                <option value="female">Femenino</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            @error('formularioApoderado.estado_marital')
                                <small class="pr-1 text-danger" role="alert"> * El campo estado civil es requerido  </small>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            @error('formularioApoderado.sexo')
                                <small class="pr-1 text-danger" role="alert"> * El campo sexo es requerido </small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center  "  >
                <span wire:loading wire:target="buscar_interno"  >  Buscando apoderado ...</span>
                <span wire:loading wire:target="update, create"  > Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> {{  $idRelacionApoderado? 'Actualizar': 'Guardar'}} informaciòn del apoderado </button>
                @if ($idRelacionApoderado)
                    <button class="btn btn-sm btn-success" type="button" style="padding: .75rem 3rem" wire:click="initialState" > Limpiar formulario </button>
                @endif
            </div>
        </form>

    </div>
    @push('scripts')
    <script>
        $(document).ready(()=>{
            Livewire.emit('pagina-cargada-apoderado');

            Livewire.on( 'data-autocomplete-apoderado', ({ distritos, ocupaciones  })=>{
                console.log(distritos);
                console.log(ocupaciones);
                $( "#distrito_apoderado" ).typeahead({
                    source: distritos
                });

                $( "#ocupacion_apoderado" ).typeahead({
                    source: ocupaciones
                });

                $( "#distrito_apoderado" ).change( (e) => {
                    Livewire.emit('change-props-apoderado', { name: e.target.name, value: e.target.value   });
                } );
                $( "#ocupacion_apoderado" ).change( (e) => {
                    Livewire.emit('change-props-apoderado', { name: e.target.name, value: e.target.value   });
                } );
            });
        });
    </script>
    @endpush
</div>



