<div  wire:ignore.self>
    <form class="row" wire:submit.prevent=" {{ $examen_id? 'update' : 'create' }} " >



        <div class="col-sm-6 form-horizontal " style="border-right: 3px solid #e3e3e3;  " >
            <h4 class="text-center text-uppercase"><strong>Cursos disponibles</strong></h4>
            <div class="row">
                @foreach ($cursosSeleccionados as $index=>$cursoCheck)
                <div class="col-md-6 row">
                    <label for="curso_{{$cursoCheck['curso_id']}}" class="col-8 col-sm-10 control-label" title="{{$cursoCheck['curso_nombre']}}"> {{$cursoCheck['curso_nombre_corto']}} </label>
                    <div class="col-4 col-sm-2">
                        <input type="checkbox" class="" id="curso_{{$cursoCheck['curso_id']}}"  wire:model = '{{ "cursosSeleccionados.$index.curso_check" }}'>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-sm-6 form-horizontal">

        </div>


























    </form>

    @push('scripts')
        <script>

        </script>
    @endpush
</div>
