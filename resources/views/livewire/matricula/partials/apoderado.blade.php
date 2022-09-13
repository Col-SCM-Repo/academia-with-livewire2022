<div class="ibox">
    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5> APODERADOS REGISTRADOS </h5>
            </div>
            @if ($idEstudiante)
                <div class="ibox-tools">
                    <button class="btn btn-xs btn-success" wire:click="nuevoApoderado">  <i class="fa fa-plus" aria-hidden="true"></i> Nuevo </button>
                </div>
            @endif
        </span>
    </div>
    <div class="ibox-content">
        @if ($idEstudiante)
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th scope="col" >Codigo</th>
                        <th scope="col" >DNI</th>
                        <th scope="col" >Apellidos y nombres</th>
                        <th scope="col" >Parentesco</th>
                        <th scope="col" >Acciones</th>
                    </tr>
                </thead>
                <tbody style="cursor: pointer;">
                        @if (count($lista_apoderados)>0)
                            @foreach ($lista_apoderados as $apoderado)
                                <tr>
                                    <td scope="row">{{$apoderado->relacion_id}}</td>
                                    <td>{{$apoderado->dni}}</td>
                                    <td>{{$apoderado->apellidos.', '.$apoderado->nombres}}</td>
                                    <td>{{$apoderado->parentesco}}</td>
                                    <td>
                                        <button class="btn btn-xs btn-success" title="Editar apoderado" wire:click="editarApoderado({{ $apoderado->relacion_id }})"> <i class="fa fa-pencil" aria-hidden="true"></i> </button>
                                        <button class="btn btn-xs btn-danger btn-delete-apoderado" title="Eliminar apoderado" data-targetname="{{$apoderado->parentesco}}" data-target="{{ $apoderado->relacion_id }}"> X </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class=" text-center " colspan="5"> <span>Sin registros</span> </td>
                            </tr>
                        @endif
                </tbody>
            </table>
        @else
            <h5>No se encontrò estudiante. </h5>
        @endif

        <div>
            <!-- begin: Modal apoderado -->
                <x-modal-form-lg idForm='form-modal-apoderado' :titulo="$relative_id? 'EDITAR APODERADO' :'NUEVO APODERADO'">
                    <form class="row " wire:submit.prevent="{{ $relative_id? 'update' : 'create' }}">
                        <div class="col-lg-6">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2 col-lg-3 control-label">DNI</label>
                                    <div class="col-md-10 col-lg-9">
                                        <div class="input-group ">
                                            <input type="text" wire:model="dni"
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
                                        <small class="help-block m-b-none text-muted"> Presionar el boton para buscar en la base de datos interna </small>
                                        <x-input-error variable='dni'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-lg-3 control-label">F Nac.</label>
                                    <div class="col-md-10 col-lg-9">
                                        <div class="input-group ">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="date" autocomplete="off " wire:model.defer="fecha_nacimiento"
                                                class="form-control" placeholder="01/01/2000" id="f_nacimiento">
                                        </div>
                                        <x-input-error variable='fecha_nacimiento'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-md-2 col-lg-3 control-label">Telefono:</label>
                                    <div class="col-md-10 col-lg-9">
                                        <input type="text" wire:model.defer="telefono"
                                            title="Ingrese un numero de contacto. " class="form-control text-uppercase">
                                        <x-input-error variable='telefono'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-md-2 col-lg-3 control-label" >Distrito</label>
                                    <div class="col-md-10 col-lg-9">
                                        <input type="text" autocomplete="off" wire:model.defer="distrito" wire:change="$set('distrito', $event.target.value)"
                                            id="distrito_apoderado" title="Distrito de procedencia. "
                                            class="text-uppercase form-control">
                                        <x-input-error variable='distrito'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-md-2 col-lg-3 control-label">Dirección</label>
                                    <div class="col-md-10 col-lg-9">
                                        <input type="text" wire:model.defer="direccion"
                                            title="Direciòn de procedencia. " class="form-control text-uppercase">
                                        <x-input-error variable='direccion'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-md-2 col-lg-3 control-label">Parentesco</label>
                                    <div class="col-md-10 col-lg-9">
                                        <select class="form-control" wire:model.defer="parentesco">
                                            <option value="">SELECCIONE</option>
                                            <option value="father">PADRE</option>
                                            <option value="mother">MADRE</option>
                                            <option value="brother">HERMANO</option>
                                            <option value="sister">HERMANA</option>
                                            <option value="uncle">TIO(A)</option>
                                            <option value="grandparent">ABUELO(A)</option>
                                            <option value="cousin">PRIMO</option>
                                            <option value="other">OTRO</option>
                                        </select>
                                        <x-input-error variable='parentesco'> </x-input-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-horizontal">
                                <div class="form-group"><label class="col-md-2 col-lg-3 control-label">Nombres:</label>
                                    <div class="col-md-10 col-lg-9">
                                        <input type="text" wire:model.defer="nombres"
                                            title="Nombres completos del alumno." class="form-control text-uppercase">
                                        <x-input-error variable='nombres'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-md-2 col-lg-3 control-label">A.Paterno</label>
                                    <div class="col-md-10 col-lg-9">
                                        <input type="text" wire:model.defer="apellido_paterno"
                                            title="Apellido paterno del alumno." class="form-control text-uppercase">
                                        <x-input-error variable='apellido_paterno'> </x-input-error>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-md-2 col-lg-3 control-label">A.Materno</label>
                                    <div class="col-md-10 col-lg-9">
                                        <input type="text" wire:model.defer="apellido_materno"
                                            title="Apellido materno del alumno." class="form-control  text-uppercase">
                                        <x-input-error variable='apellido_materno'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-lg-3 control-label">Ocupacion.</label>
                                    <div class="col-md-10 col-lg-9">
                                        <input type="text" autocomplete="off" wire:model.defer="ocupacion" wire:change="$set('ocupacion', $event.target.value)"
                                            title="Ocupacion del apoderado" id="ocupacion_apoderado"
                                            class="form-control typeahead text-uppercase" data-provide="typeahead">
                                        <x-input-error variable='ocupacion'> </x-input-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-lg-3 control-label">E. Civil</label>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="input-group ">
                                            <select wire:model.defer="estado_marital" class="form-control">
                                                <option value="">Seleccione</option>
                                                <option value="single">Soltero(a)</option>
                                                <option value="married">Casado(a)</option>
                                                <option value="divorcied">Divorciado(a)</option>
                                                <option value="widower">Viudo(a)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <label class="col-md-2 col-lg-1 control-label">Sexo</label>
                                    <div class="col-md-4 col-lg-4">
                                        <select wire:model.defer="sexo" class="form-control">
                                            <option value="">Seleccione</option>
                                            <option value="male">Masculino</option>
                                            <option value="female">Femenino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        @error('estado_marital') <small class="pr-1 text-danger" role="alert"> * El campo estado civil es requerido </small> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        @error('sexo') <small class="pr-1 text-danger" role="alert"> * El campo sexo es requerido </small> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center  ">
                            <span wire:loading wire:target="buscar_interno"> Buscando apoderado ...</span>
                            <span wire:loading wire:target="update, create"> Guardando ...</span>
                            <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem"> {{
                                $relative_id? 'Actualizar': 'Guardar'}} </button>
                        </div>
                    </form>
                </x-modal-form-lg>
            <!-- end: Modal apoderado -->
        </div>

    </div>

    <script>

    </script>

    @push('scripts')
        <script>
            $(document).ready(()=>{
                Livewire.emit('pagina-cargada-apoderado');
                Livewire.on( 'data-autocomplete-apoderado', ({ distritos, ocupaciones  })=>{
                    $( "#distrito_apoderado" ).typeahead({ source: distritos });
                    $( "#ocupacion_apoderado" ).typeahead({ source: ocupaciones });
                });
            });

            document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (msg, {fingerprint}) => {
                if(fingerprint.name == 'matricula.partials.apoderado'){
                [...document.getElementsByClassName('btn-delete-apoderado')].forEach((el)=>{
                    el.addEventListener('click', ({target})=>{
                        if( target.dataset.targetname ){
                            swal({
                            title: "Estas Seguro?",
                            text: "Se eliminara el(la) "+target.dataset.targetname+" del estudiante",
                            icon: "warning",
                            buttons: true,
                            buttons: ["Cancelar", "Eliminar"],
                            dangerMode: true,
                            })
                            .then((willDelete) => {
                                if (willDelete)
                                        Livewire.emit('eliminar-apoderado',target.dataset.target )

                            });
                            }

                        })
                    })
                    }
                })
            });

        </script>
    @endpush

</div>
