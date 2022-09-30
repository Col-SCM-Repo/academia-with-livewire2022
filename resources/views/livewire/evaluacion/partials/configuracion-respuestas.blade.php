<div>
    @if ( $listaPreguntasExamen && count($listaPreguntasExamen)>0 )
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr>
                    <th>N. Pregunta</th>
                    <th>Respuesta</th>
                    <th>Puntaje</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($listaPreguntasExamen as $index=>$preguntaExamen)
                        <tr>
                            <td scope="row"> {{ $preguntaExamen->question_number }} </td>
                            <td>
                                <select wire:model='{{ "listaPreguntasExamen.$index.correct_answer" }}'>
                                    <option value="">-</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                            </td>
                            <td>{{ $preguntaExamen->score }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>


    @else
        <div class="text-center">
            <small>No se encontró preguntas de examen</small>
        </div>
    @endif
</div>
