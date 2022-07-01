@extends('reports.incidentes.pdf.template')
@section('title','Reporte especifico - Colegio Cabrera')


@section('styles')
    <style>
        *{
            font-size: 14px;
        }
        footer{
            width: 80%;
            position: fixed; 
            border:  2px solid #030303; 
            bottom: 2.3cm; 
            left: 3cm; 
            text-align: center;
        }
        
        #img-header img{
            position: absolute;
            width: 6em;
            top: 2em;
            right: 10em;
        }

    </style>

@endsection

@section('informacion_reporte')


<br>
<div>
    <table border="1" style=" width: 700px; text-align: center; padding: 1em; margin: auto; ">
        <tr>
            <th colspan="4" style="text-transform: uppercase" class="background_shadow">
                Reporte especifico
            </th>
        </tr>
        <tr>
            <td rowspan="3" class="background_shadow" style="width: 150px"> APELLIDOS Y NOMBRESZZZ:</td>
            <td rowspan="3" style="text-transform: uppercase; text-align: left; padding-left: .5rem;"> 
                {{$data->student->entity->father_lastname}} {{$data->student->entity->mother_lastname}} {{$data->student->entity->name}} 
            </td>
            <td class="background_shadow" style="width: 100px;">PERIODO </td>
            <td style="width: 150px; text-align: left; padding-left: .5rem;">  {{ $data->classroom->level->period->name }}  </td>
        </tr>
        <tr>
            <td class="background_shadow">NIVEL</td>
            <td style="text-align: left; padding-left: .5rem;"> {{ $data->classroom->level->level_type->description }} </td>
        </tr>
        <tr>
            <td class="background_shadow">AÑO</td>
            <td style="text-align: left; padding-left: .5rem;"> {{ $data->classroom->level->period->year }} </td>
        </tr>
    </table>
</div>
<br>
@endsection

@section('content')
<br>
<hr>  
<table class="table table-striped table-sm table-light table-striped "  border="1">
    <thead>
        <tr>
            <th style="width: 25px;"    class="background_shadow text-center">Nº</th>
            <th style="width: 60px;"    class="background_shadow text-center">Fecha</th>
            <th style="width: 60px;"    class="background_shadow text-center">Hora</th>
            <th style="width: 85px;"    class="background_shadow text-center">T. Incidente</th>
            <th style="width: 80px;"    class="background_shadow col-4">Reportó</th>
            <th style="width: 185px"    class="background_shadow text-center">Descripción Incidente</th>
            <th style="width: 60px;"    class="background_shadow text-center">Fecha</th>
            <th style="width: 60px;"    class="background_shadow text-center">Hora</th>
            <th style="width: 80px;"    class="background_shadow text-center">Justificó</th>
            <th style="width: 60px;"    class="background_shadow text-center">Receptor</th>
            <th style="width: 185px"    class="background_shadow text-center">Justificacion</th>
            <th style="width: 70px;"    class="background_shadow text-center">Estado</th>
        </tr>
    </thead>
    <tbody class="">
        <?php $numI=1 ?>
        <!-- Accediendo a cada incidencias registradaa -->
        @if (count($data->incidentes))
            @foreach ($data->incidentes as $incidente)
                <tr  style="{{ $incidente->estado == '0'? ' background: #E0AFA0;':'' }}" >
                    <td > {{$numI++}}</td>
                    <td > {{Carbon\Carbon::parse($incidente->created_at)->format('Y/m/d')}}</td></td>
                    <td > {{Carbon\Carbon::parse($incidente->created_at)->format('h:i:s a')}}</td>
                    <td > {{$incidente->tipo_incidente}}</td>
                    <td > {{$incidente->auxiliar->entity->name }} {{$incidente->auxiliar->entity->father_lastname }}</td>
                    <td style="text-align: justify;"> {{$incidente->descripcion}}</td>
                    
                    @if ( $incidente->estado == "1" )
                        <td> {{ Carbon\Carbon::parse($incidente->fecha_reporte)->format('Y/m/d')}}</td>
                        <td> {{ Carbon\Carbon::parse($incidente->fecha_reporte)->format('h:i:s a')}}</td>
                        <td> {{$incidente->secretaria->entity->name }} {{$incidente->secretaria->entity->father_lastname }}</td>
                        <td> {{$incidente->parentesco}}</td>
                        <td style="text-align: justify;"> {{$incidente->justificacion}}</td>
                        <td> Justificado</td>
                    @else
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> No justificado</td>
                    @endif

                </tr>
            @endforeach                         
        @else
            <tr>
                <td colspan="12" style="text-align: center; padding: 30px"  > No se encontraron incidencias registradas.</td>
            </tr>
        @endif
    </tbody>
</table>
<br>
<p style="width: 100%; text-align: right; font-size: 0.75em">Fecha de generacion del reporte: {{date("Y/m/d H:i:s a")}}</p>
@endsection

