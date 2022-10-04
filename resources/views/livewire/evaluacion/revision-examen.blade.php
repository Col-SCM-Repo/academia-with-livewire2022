<div class="ibox">
    @push('styles')
        <style>
            .input-search-date{
                background: #ffe8beab;
                border: 1px solid #979797;
                border-radius: 2px:
            }
            .input-search-date:focus{
                background: #fff;
                outline: 1px solid #1BB394;

            }

        </style>
    @endpush

    <div class="ibox-content" style="padding-right: 0; padding-left: 0; background: #fff;">
        <div style="display: flex; ">
            <h5 class="text-uppercase" style="flex-grow: 1">Revisión de examenes: &nbsp; </h5>
            <form>
                <label for="rango_inicio">Del:</label> <input class="input-search-date" style="display: inline-block" type="date"  id="rango_inicio" wire:model.defer="fechaInicio">
                <label for="rango_fin">al:</label> <input class="input-search-date" style="display: inline-block" type="date"  id="rango_fin" wire:model.defer="fechaFin">
                <button class="btn btn-xs btn-primary" type="button" wire:click='onClickBtnSearchExams'> <i class="fa fa-search" aria-hidden="true"></i> </button>
            </form>
        </div>
        <hr>
        <div >
            @if ($listaExamenesDisponibles)
                <table class="table table-hover  table-stripped table-inverse table-responsive">
                    <thead class="thead-inverse">
                        <tr>
                            <th>CODIGO</th>
                            <th>NOMBRE</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>
                            <th>FECHA</th>
                            <th>ACCIONES</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if ( count($listaExamenesDisponibles) > 0 )
                                @foreach ($listaExamenesDisponibles as $examn)
                                @php
                                @endphp
                                    <tr>
                                        <td scope="row"> {{ $examn['id'] }}  </td>
                                        <td> {{ $examn['name'] }} </td>
                                        <td> {{ $examn['evaluation_type'] }} </td>
                                        <td> {{ $examn['status']  }} </td>
                                        <td> {{ $examn['exam_date'] }} </td>
                                        <td>
                                            <input type="file" wire:model="archivoCartilla">
                                            <button class="btn btn-xs btn-success" {{ $examn['disabled_cartilla'] ? '' : 'disabled' }} >    <i class="fa fa-upload" aria-hidden="true"></i> Cargar cartila. respuestas </button>
                                            <button class="btn btn-xs btn-danger"  {{ $examn['disabled_corregir'] ? '' : 'disabled' }}  > <i class="fa fa-magic" aria-hidden="true"></i> Corregir examen </button>
                                            <button class="btn btn-xs btn-primary" {{ $examn['disabled_resultados'] ? '' : 'disabled' }}> <i class="fa fa-file" aria-hidden="true"></i> Ver resultados</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center" style="padding: 3rem; background: #c8c8c847;"> No se encontró registros de usuarios para el rango de fecha indicado.  </td>
                                </tr>
                            @endif
                        </tbody>
                </table>
            @else
                <div class="text-center" style="padding: 3rem; background: #c8c8c847;">
                    Aun no se especifica el rango de busqueda para los examenes.
                </div>
            @endif
        </div>

    </div>
</div>
