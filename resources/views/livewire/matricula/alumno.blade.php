<!------------------------------- Begin: alumno ------------------------------->
<div class="ibox" wire:ignore.self>

    <div class='loader'></div>

    <div class="ibox-title">
        <span>
            <h5> {{ $idEstudiante? 'Alumno existente' : 'Nuevo alumno' }} </h5>
        </span>
    </div>
    <div class="ibox-content">
        <form class="row "  wire:submit.prevent="{{ $idEstudiante? 'update' : 'create' }}">
            <div class="col-lg-6 form-horizontal" >
                <div class="form-group">
                    <label class="col-lg-2 control-label">DNI</label>
                    <div class="col-lg-10">
                        <div class="input-group ">
                            <input type="text" wire:model.defer="formularioAlumno.dni"
                                placeholder="DNI o carnet de extranjeria "
                                class="form-control ">
                            <span class="input-group-btn">
                                <button type="button" wire:click="buscar_interno"
                                    class="btn btn-outline btn-primary" title="Buscar en la base de datos interna">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                                <button type="button" wire:click="buscar_externo"
                                    class="btn btn-outline btn-warning" title="Buscar en la base de datos externa">
                                    <i class="fa fa-archive" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                        <small class="help-block m-b-none text-muted"> 
                            Presionar el boton para buscar al alumno 
                        </small>
                        <x-input-error variable='formularioAlumno.dni'> </x-input-error>
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
                <div class="form-group">
                    <label class="col-lg-2 control-label">Telefono:</label>
                    <div class="col-lg-10">
                        <input type="text" wire:model.defer="formularioAlumno.telefono" class="form-control ">
                        <x-input-error variable='formularioAlumno.telefono'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Distrito</label>
                    <div class="col-lg-10">
                        <input type="text" autocomplete="of" wire:model.defer="formularioAlumno.distrito" id="distrito" 
                        class=" form-control">
                        <x-input-error variable='formularioAlumno.distrito'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Dirección</label>
                    <div class="col-lg-10">
                        <input type="text" wire:model.defer="formularioAlumno.direccion" class="form-control">
                        <x-input-error variable='formularioAlumno.direccion'> </x-input-error>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 form-horizontal">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Nombres:</label>
                    <div class="col-lg-10">
                        <input type="text" wire:model.defer="formularioAlumno.nombres" class="form-control">
                        <x-input-error variable='formularioAlumno.nombres'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">A.Paterno</label>
                    <div class="col-lg-10">
                        <input type="text" wire:model.defer="formularioAlumno.ap_paterno" class="form-control">
                        <x-input-error variable='formularioAlumno.ap_paterno'> </x-input-error>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">A.Materno</label>
                    <div class="col-lg-10">
                        <input type="text" wire:model.defer="formularioAlumno.ap_materno" class="form-control ">
                        <x-input-error variable='formularioAlumno.ap_materno'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">I.E. Proc.</label>
                    <div class="col-lg-10">
                        <input type="text" autocomplete="of" wire:model.defer="formularioAlumno.Ie_procedencia"
                        id="Ie_procedencia" class="form-control typeahead " data-provide="typeahead">
                        <x-input-error variable='formularioAlumno.Ie_procedencia'> </x-input-error>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Egreso</label>
                    <div class="col-lg-4">
                        <div class="input-group ">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" autocomplete="of" wire:model.defer="formularioAlumno.anio_egreso"
                                class="form-control" placeholder="2022" id="datepicker-year">
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
                        <x-input-error variable='formularioAlumno.anio_egreso'> </x-input-error>
                    </div>
                    <div class="col-lg-6">
                        <x-input-error variable='formularioAlumno.sexo'> </x-input-error>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right" >
                <span wire:loading wire:target="buscar_interno, buscar_externo"> Buscando alumno ...</span>
                <span wire:loading wire:target="update, create"> Guardando ...</span>
                <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> 
                    {{ $idEstudiante? 'Actualizar': 'Guardar'}} 
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 
    $('#distrito').autocomplete({source: ['css', 'html', 'javascript', 'react']})
--}}

<!------------------------------- End: alumno ------------------------------->
