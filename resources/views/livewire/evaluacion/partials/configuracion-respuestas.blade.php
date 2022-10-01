<div>
    <div class="ibox">
        <div class="ibox-titlte border-bottom white-bg dashboard-header" style="display: flex">
            <h3 style="flex-grow: 1">RESPUESTAS DEL EXAMEN</h3>
            <div class="ibox-tools" >
                <button class="btn btn-sm btn-success" target="_blank"> <i class="fa fa-download" aria-hidden="true"></i> Descargar cartilla de respuestas  </button>
            </div>
        </div>
        <div class="ibox-content" style="padding: 0; padding-bottom: 1rem">
            @if ( $listaPreguntasExamen && count($listaPreguntasExamen)>0 )
            <div style="display: flex; flex-wrap: wrap;">
                @foreach ($listaPreguntasExamen as $i=>$curso)
                    <div class=" " style="padding-left: 3rem;">
                        <table class="table table-hover table-striped table-inverse table-responsive ">
                            <thead class="thead-inverse">
                                <tr>
                                    <th colspan="2" class="text-center" title="{{ $curso['nombre'] }}" style="border-bottom: 1px solid #afa8a8">
                                        {{ strtoupper($curso['nombre_corto']) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Respuesta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($curso['preguntas'] as $j=>$pregunta)
                                    <tr>
                                        <td style="padding-top: 2px; padding-bottom: 2px;" scope="row" title="Numero de pregunta"> {{$pregunta['numero']}} </td>
                                        <td style="padding-top: 2px; padding-bottom: 2px;">
                                            <select wire:model.defer='{{ "listaPreguntasExamen.$i.preguntas.$j.respuesta" }}' wire:change="almacenarNota( {{$pregunta['id']}}, $event.target.value )" class="form-control">
                                                <option value="">-</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center">
                <small>No se encontró preguntas de examen</small>
            </div>
        @endif
        </div>
    </div>

</div>
