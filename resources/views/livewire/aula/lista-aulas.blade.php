<div>
    @if ( $nivel)
        <h4> Nivel: {{ $nivel->level_type->description}} </h4>
        @if (count($nivel->classrooms)>0 )
            <div style="display: flex; margin: 1rem;">
                @foreach ( $nivel->classrooms as $aula)
                    <a href="{{ route('aulas.informacion', ['aula_id'=> $aula->id ]) }}" target="_blank" style="margin-right: 1rem" class="btn btn-xs btn-success" > <small>AULA {{ $aula->name }} </small></a>
                @endforeach
            </div>
        @else
            <div class="text-center">
                <small> No se encontró aulas registradas. </small>
            </div>
        @endif
    @else
        <div class="text-center">
            <small> Periodo seleccionado no valido. </small>
        </div>
    @endif

</div>
