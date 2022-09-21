<div class="ibox">
    <div class="ibox-title">
        <h5> MATRICULAS REGISTRADAS </h5>

        <div class="ibox-tools text-right" style="display: flex">
            <div>
                @if ($estudianteId)
                    <span class="label label-primary"> {{count($listaMatriculas)}} Matriculas registradas </span>
                @else
                    <span class="label label-warning-light"> Sin registrar </span>
                @endif
            </div>
            <div style="flex-grow: 1">
                <button class="btn btn-xs btn-info" type="button" wire:click='render'>
                    <i class="fa fa-refresh" aria-hidden="true"></i> Recargar
                </button>
            </div>
        </div>
    </div>
    <div class="ibox-content">

        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th scope="col">CODIGO</th>
                    <th scope="col">AÑO</th>
                    <th scope="col">NIVEL</th>
                    <th scope="col">PERIODO</th>
                    <th scope="col">AULA</th>
                    <th scope="col">ESTADO</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @if (count($listaMatriculas)>0)
                    @foreach ($listaMatriculas as $matricula)
                        <tr>
                            <th scope="row">{{ $matricula->matricula_codigo }}</th>
                            <td>{{ $matricula->anio }}</td>
                            <td>{{ $matricula->nivel }}</td>
                            <td>{{ $matricula->periodo }}</td>
                            <td>{{ $matricula->aula }}</td>
                            <td class="{{ $matricula->bg_estado }}">{{ $matricula->estado_matricula }}</td>
                            <td>{{ $matricula->fecha }}</td>
                            <td>
                                <button type="button" class="btn btn-xs btn-primary" wire:click="cargarMatricula({{ $matricula->matricula_id }})"> <i class="fa fa-eye" aria-hidden="true"></i> Ver </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td> <span>No se encontró matriculas. </span> </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
