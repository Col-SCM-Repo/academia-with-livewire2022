<div  wire:ignore.self>
    <form class="row" wire:submit.prevent=" {{ $examen_id? 'update' : 'create' }} " >



        <div class="col-sm-5 form-horizontal " style="border-right: 3px solid #000;" >
            @foreach ($cursosSeleccionados as $index=>$cursoCheck)
            <div class="">
                <label for="" class="col-md-9 control-label" > {{$cursoCheck['curso_nombre']}} </label>
                <div class="col-md-3">
                    <input type="checkbox" class=""  wire:model = '{{ "cursosSeleccionados.$index.curso_check" }}'>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-sm-4 form-horizontal">

        </div>


























    </form>

    @push('scripts')
        <script>

        </script>
    @endpush
</div>
