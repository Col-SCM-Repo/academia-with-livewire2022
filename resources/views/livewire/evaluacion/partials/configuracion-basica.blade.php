<div  wire:ignore.self>
    <form class="row" wire:submit.prevent=" {{ $examen_id? 'update' : 'create' }} " >
        <div class="col-md-7 form-horizontal ">
            <div class="form-group">
                <label class="col-lg-2 control-label">Nombre:</label>
                <div class="col-lg-10">
                    <input type="text" wire:model.defer="nombre" class="form-control" titulo="Ingrese nombre del examen">
                    <x-input-error variable='nombre'> </x-input-error>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label ">Grupo</label>
                <div class="col-lg-10">
                    <select class="form-control" wire:model.defer="grupo_id" title="Ingrese grupo del examen">
                        <option value=""> Seleccionar grupo </option>
                        @foreach ($lista_grupos as $grupo)
                            <option value="{{$grupo->id}}"> {{ $grupo->description }} </option>
                        @endforeach
                    </select>
                    <x-input-error variable='grupo_id'> </x-input-error>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label ">Nivel</label>
                <div class="col-lg-10">
                    <select class="form-control" wire:model.defer="nivel_id" title="Nivel">
                        <option value="-"> Seleccionar nivel </option>
                        @foreach ($lista_niveles as $nivel)
                            <option value="{{$nivel->id}}"> {{ $nivel->level_type->description }} </option>
                        @endforeach
                    </select>
                    <x-input-error variable='nivel_id'> </x-input-error>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label ">Fecha</label>
                <div class="col-lg-10">
                    <input type="datetime-local" class="form-control" wire:model.defer='fecha_examen'>
                    <x-input-error variable='fecha_examen'> </x-input-error>
                </div>
            </div>

        </div>
        <div class="col-md-5 form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label ">Tipo</label>
                <div class="col-lg-9">
                    <select class="form-control" wire:model.defer="tipo_examen" title="Tipo de examen">
                        <option value=""> Seleccionar tipo </option>
                        <option value="simulacrum"> SIMULACRO </option>
                        <option value="monthly"> MENSUAL </option>
                        <option value="weekly"> SEMANAL </option>
                        <option value="quick"> RAPIDO </option>
                        <option value="other"> OTRO </option>
                    </select>
                    <x-input-error variable='tipo_examen'> </x-input-error>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Codigo:</label>
                <div class="col-lg-9">
                    <input type="text" wire:model.defer="codigo_de_grupo" class="form-control" placeholder="10" title="Codigo del grupo asociada al examen">
                    <x-input-error variable='codigo_de_grupo'> </x-input-error>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Preguntas:</label>
                <div class="col-lg-9">
                    <input type="text" wire:model.defer="numero_preguntas" class="form-control" title="Numero de preguntas">
                    <x-input-error variable='numero_preguntas'> </x-input-error>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">P. inco.:</label>
                <div class="col-lg-9">
                    <input type="number" wire:model.defer="valor_preguntas_incorrectas" step="0.1" class="form-control" title="Valor para las preguntas incorrectas">
                    <x-input-error variable='valor_preguntas_incorrectas'> </x-input-error>
                </div>
            </div>


        </div>
        <div class="col-12 text-right">
            {{-- <div class="alert " role="alert">
                @error('student_id')
                    <div class="text-danger" role="alert">
                        Debe registrar al <span class="alert-link">alumno</span>
                    </div>
                @enderror
            </div> --}}
        </div>
        <div class="col-12 text-right" style="padding-right: 3rem">
            <span wire:loading wire:target="update create"> Guardando ...</span>
            <button class="btn btn-sm btn-primary" type="submit" style="padding: .75rem 3rem">
                <i class="fa fa-save    "></i> {{ $examen_id? 'Actualizar' : 'Guardar' }} configuración examen
            </button>
        </div>
    </form>

    @push('scripts')
        <script>

        </script>
    @endpush
</div>
