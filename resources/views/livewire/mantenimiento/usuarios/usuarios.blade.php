<div class="ibox">


    @section('header')
        <div class="col-md-8">
            <h2>Usuarios registrados</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="">Home</a>
                </li>
                <li>
                    <a>Mantenimiento</a>
                </li>
                <li class="active">
                    <strong>Usuarios</strong>
                </li>
            </ol>
        </div>
    @endsection


    <div class="ibox-title">
        <span style="display: flex">
            <div style="flex-grow: 1">
                <h5>  </h5>
            </div>
            <div class="ibox-tools">
                <button class="btn btn-xs btn-success" wire:click="btnOpenModalNuevoUsuario">  <i class="fa fa-plus" aria-hidden="true"></i> Nuevo </button>
            </div>
        </span>
    </div>
    <div class="ibox-content">
        <table class="table table-sm table-hover">
            <thead>





                <tr>
                    <th class="text-center text-uppercase " scope="col" >Codigo</th>
                    <th class="text-center text-uppercase " scope="col" >DNI</th>
                    <th class="text-center text-uppercase " scope="col" >Apellidos y nombres</th>
                    <th class="text-center text-uppercase " scope="col" >Tipo</th>
                    <th class="text-center text-uppercase " scope="col" >Estado</th>
                </tr>
            </thead>
            <tbody style="cursor: pointer;">
                    @if ( $listaUsuarios && count($listaUsuarios)>0)
                        @foreach ($listaUsuarios as $usuario)
                            <tr>
                                <td class="text-center"  scope="row">{{$usuario['usuario_id']}}</td>
                                <td class="text-center" >{{$usuario['dni']}}</td>
                                <td class="text-uppercase " >{{$usuario['nombres']}}</td>
                                <td class="text-center text-uppercase " >{{$usuario['tipo']}}</td>
                                <td class="text-center text-uppercase " >{{$usuario['estado']}}</td>

                                <td>
                                    <button class="btn btn-xs btn-success" title="Editar usuario" wire:click="btnOpenModalEditUsuario({{ $usuario['usuario_id'] }})"> <i class="fa fa-pencil" aria-hidden="true"></i> </button>
                                    <button class="btn btn-xs btn-danger btn-delete-usuario" title="Eliminar usuario" data-targetname="{{$usuario['nombres']}}" data-target="{{ $usuario['usuario_id'] }}"> X </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class=" text-center " colspan="5" style="padding: 5rem;"> <span>No se encontró usuarios.</span> </td>
                        </tr>
                    @endif
            </tbody>
        </table>


    </div>


        <!-- begin: Modal usuario -->
        <div>
            <x-modal-form-lg idForm='form-modal-usuarios' :titulo="$usuarioId? 'EDITAR USUARIO' :'NUEVO USUARIO'">

                <div class="tabs-nueva-matricula " style="min-height: 85vh">
                    <ul class="nav nav-tabs">
                        <li class=" active"><a data-toggle="tab" href="#tab-1"> DATOS PERSONALES</a></li>
                        <li class=" "><a data-toggle="tab" href="#tab-2">DATOS USUARIO</a></li>
                    </ul>
                    <div class="tab-content">

                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body ">
                                <form  wire:submit.prevent="{{ $entidadId? 'actualizarDatosPersonales':'registrarDatosPersonales' }}"wire:ignore.self >
                                    <div class="form-horizontal col-sm-6" style="margin-right:3rem; ">
                                        <div class="form-group">
                                            <label for="txt-ap-paterno">Apellido paterno</label>
                                            <input type="text" class="form-control" id="txt-ap-paterno" wire:model.defer="apellido_paterno">
                                            <x-input-error variable='apellido_paterno'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="txt-ap-materno">Apellido materno</label>
                                            <input type="text" class="form-control" id="txt-ap-materno" wire:model.defer="apellido_materno">
                                            <x-input-error variable='apellido_materno'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="txt-nombre">Nombre</label>
                                            <input type="text" class="form-control" id="txt-nombre" wire:model.defer="nombre">
                                            <x-input-error variable='nombre'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="select-genero" >Genero</label>
                                            <select class="form-control" id="select-genero" wire:model.defer="genero" >
                                                <option value="">SELECCIONE GENERO</option>
                                                <option value="male">  MASCULINO </option>
                                                <option value="female">  FEMENINO </option>
                                            </select>
                                            <x-input-error variable='genero'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="txt-fecha-nacimiento">Fecha nacimiento</label>
                                            <input type="date" class="form-control" id="txt-fecha-nacimiento" wire:model.defer="fecha_nacimiento">
                                            <x-input-error variable='fecha_nacimiento'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="select-estado-marital">Estado marital</label>
                                            <select class="form-control" id="select-estado-marital" wire:model.defer="estado_marital" >
                                                <option value="">SELECCIONE ESTADO MARITAL </option>
                                                <option value="single"> SOLTERO(A) </option>
                                                <option value="married"> CASADO(A) </option>
                                                <option value="divorcied"> DIVORCIADO(A) </option>
                                                <option value="widower"> VIUDO(A) </option>
                                            </select>
                                            <x-input-error variable='estado_marital'> </x-input-error>
                                        </div>
                                    </div>

                                    <div class="form-horizontal col-sm-5">
                                        <div class="form-group">
                                            <label for="txt-nro-documento">Documento de identidad</label>
                                            <div style="display: flex">
                                                <input type="text" style="flex-grow: 1 " class="form-control" id="txt-nro-documento" wire:model.defer="numero_documento">
                                                <div>
                                                    <button type="button" class="btn  btn-primary"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                                </div>
                                            </div>
                                            <x-input-error variable='numero_documento'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="txt-celular">Celular</label>
                                            <input type="text" class="form-control" id="txt-celular" wire:model.defer="celular">
                                            <x-input-error variable='celular'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="txt-email">Email</label>
                                            <input type="email" class="form-control" id="txt-email" wire:model.defer="email">
                                            <x-input-error variable='email'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="select-grado-instruccion">Grado de instrucción</label>
                                            <select id="select-grado-instruccion" class="form-control" wire:model.defer="grado_de_instruccion">
                                                <option value="">SELECCIONE GRADO DE INSTRUCCIÓN</option>
                                                <option value="none"> NINGUNA </option>
                                                <option value="elementary_school"> ESCUELA PRIMARIA </option>
                                                <option value="high_school"> ESCUELA SECUNDARIA </option>
                                                <option value="universitary_education"> EDUCACIÓN UNIVERSITARIA </option>
                                            </select>
                                            <x-input-error variable='grado_de_instruccion'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="txt-distrito">Distrito</label>
                                            <input type="text" class="form-control" id="txt-distrito" wire:model.defer="distrito">
                                            <x-input-error variable='distrito'> </x-input-error>
                                        </div>
                                        <div class="form-group">
                                          <label for="txt-direccion">Dirección</label>
                                          <input type="text" class="form-control" id="txt-direccion" wire:model.defer="direccion">
                                          <x-input-error variable='direccion'> </x-input-error>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-right">
                                        <div class="text-mutted text-right" wire:loading wire:target="actualizarDatosPersonales, registrarDatosPersonales" > <small>Guardando...</small> </div>
                                        <button type="submit" class="btn-sm btn-primary"> {{ $entidadId? 'Actualizar':'Guardar' }} datos personales  </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body ">

                                <form wire:submit.prevent="{{ $usuarioId? 'actualizarUsuario':'registrarUsuario' }}" wire:ignore.self >
                                    @if ($entidadId)
                                        <div class="horizontal col-sm-8 col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="">Usuario</label>
                                                <input type="text" class="form-control" id="" wire:model.defer="usuario">
                                                <x-input-error variable='usuario'> </x-input-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Tipo de usuario</label>
                                                <input type="text" class="form-control" id="" wire:model.defer="tipo_usuario">
                                                <x-input-error variable='tipo_usuario'> </x-input-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Estado</label>
                                                <input type="text" class="form-control" id="" wire:model.defer="estado">
                                                <x-input-error variable='estado'> </x-input-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Contraseña</label>
                                                <input type="text" class="form-control" id="" wire:model.defer="contrasenia">
                                                <x-input-error variable='contrasenia'> </x-input-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Repita contraseña</label>
                                                <input type="text" class="form-control" id="" wire:model.defer="contrasenia_confirmacion">
                                                <x-input-error variable='contrasenia_confirmacion'> </x-input-error>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 text-right">
                                            <div class="text-mutted text-right" wire:loading wire:target="registrarUsuario, actualizarUsuario" >
                                                <small>Guardando...</small>
                                            </div>
                                            <button type="submit" class="btn-sm btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                {{ $usuarioId? 'Actualizar':'Guardar' }} usuario
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-center"><small class="text-muted">Debe registrar los datos personales para continuar. </small></div>
                                    @endif
                                </form>
                            </div>
                        </div>



                    </div>
                </div>

            </x-modal-form-lg>
        </div>
        <!-- end: Modal usuario -->


    <script>

    </script>

</div>
