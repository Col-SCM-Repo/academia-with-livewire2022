<!------------------------------- Begin: apoderado ------------------------------->
<div class="ibox" wire:ignore.self>
    <div class="ibox-title">
        <span>
            <h5> {{  $apoderado_id? 'Apoderado existente' : 'Nuevo apoderado' }} </h5>
        </span>
    </div>
    <div class="ibox-content">
        <form class="row " wire:submit.prevent = "{{ $apoderado_id? 'updateApoderado' : 'createApoderado' }}" >
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
                                </span> 
                            </div>
                            @error('formularioApoderado.dni') <div class="pr-1 text-danger" role="alert"> * {{ $message }} </div> @enderror
                            <span class="help-block m-b-none text-muted"> Presionar el boton de buscar para obtener informacion del apoderado  </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">F Nac.</label>
                        <div class="col-lg-10">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="date" autocomplete="of" wire:model.defer="formularioApoderado.f_nac" class="form-control"  placeholder="01/01/2000" id="f_nacimiento">
                            </div>
                            @error('formularioApoderado.f_nac') <div class="pr-1 text-danger" role="alert"> * El campo fecha de nacimiento es requerido </div> @enderror
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
                            @error('formularioApoderado.estado_marital') <div class="pr-1 text-danger" role="alert"> * El campo estado civil es requerido </div> @enderror
                        </div>
                        <div class="col-lg-6">
                            @error('formularioApoderado.sexo') <div class="pr-1 text-danger" role="alert"> * El campo sexo es requerido </div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center  "  > 
                <span wire:loading wire:target="buscar_interno"  >  Buscando apoderado ...</span>
                <span wire:loading wire:target="update, create"  > Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> {{  $apoderado_id? 'Actualizar': 'Guardar'}} informaciòn del apoderado </button>
                @if ($apoderado_id)
                    <button class="btn btn-sm btn-success" type="button" style="padding: .75rem 3rem" wire:click="initialState" > Limpiar formulario </button>
                @endif
            </div>
        </form>
        
    </div>
    @push('scripts')
        
    @endpush
</div>
<!------------------------------- End: apoderdado ------------------------------->
