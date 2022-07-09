<div style="text-align: right">
    Numero de evidencias: {{count($evidencias)}}
</div>
<table style="width: 100%; max-width:700px; margin: auto; margin-bottom: 16px;" >
    <thead>
        <tr>
                <td style="width: 50px;">Nº</td>
                <td>Descripcion</td>
                <td style="width: 250px;">Acciones</td>
        </tr>
    </thead>
    <tbody>
        @if (count($evidencias)>0)
            @foreach ($evidencias as $key=>$evidencia)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$evidencia->evidencia_descripcion}}</td>
                    <td>
                        <a class="text-success" target="_blank" href="{{ asset('uploads/evidencias/'.$evidencia->path) }}"> Ver <i class="fa fa-eye" aria-hidden="true"></i> </a> &nbsp;
                        <a class="text-secondary" download href="{{ asset('uploads/evidencias/'.$evidencia->path) }}"> Descargar  <i class="fa fa-download" aria-hidden="true"></i> </a>&nbsp;
                        <a class="text-danger btn-eliminar-evidencia" data-target="{{ $evidencia->id}}" href="{{ action('EvidenciaController@destroy', ['id'=>$evidencia->id]) }}"   > Eliminar  <i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3" style="text-align: center"> Aun no registra ninguna evidencia.</td>
            </tr>
        @endif
    </tbody>
</table>





