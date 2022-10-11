<div class="ibox">
    <div class="ibox-title" style="display: flex">
        <h4 style="flex-grow: 1">ALUMNOS MATRICULADOS</h4>
        <div class="ibox-tools" >
            Buscar: <input type="text" wire:model="search" style="min-width: 30rem" placeholder="Ingrese nombres, apellidos o dni " >
        </div>
    </div>
    <div class="ibox-content" style="padding-top: 0">
        <table class="table table-striped table-inverse table-responsive" style="width: 100%">
            <thead class="thead-inverse">
                <tr style="background: #afafaa63">
                    <th style="padding-top: 2px; padding-bottom: 2px;" class="text-center" >CODIGO</th>
                    <th style="padding-top: 2px; padding-bottom: 2px;" >APELLIDOS Y NOMBRES</th>
                    <th style="padding-top: 2px; padding-bottom: 2px;" class="text-center" >DNI</th>
                    <th style="padding-top: 2px; padding-bottom: 2px;" class="text-center" >ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($listaAlumnos as $alumno)
                        <tr >
                            <td style="padding-top: 2px; padding-bottom: 2px;" scope="row" class="text-center" >{{ $alumno['code'] }}</td>
                            <td style="padding-top: 2px; padding-bottom: 2px;"> {{ $alumno['father_lastname'].' '.$alumno['mother_lastname'].', '.$alumno['name']  }} </td>
                            <td style="padding-top: 2px; padding-bottom: 2px;" class="text-center" > {{ $alumno['document_number'] }} </td>
                            <td style="padding-top: 2px; padding-bottom: 2px;" class="text-right">
                                <button type="button"  class="btn btn-xs text-danger" disabled > <i class="fa fa-eye" aria-hidden="true"></i> Informacion </button>
                                <button type="button"  class="btn btn-xs text-success" > <i class="fa fa-eye" aria-hidden="true"></i> Evaluaciones </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
        <div class="text-right">
            <small style="font-size: 1.250rem">Mostrando: {{ count($listaAlumnos ) }} alumnos </small>
        </div>
    </div>
</div>
