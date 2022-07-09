
<?php
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
header('Content-Disposition: attachment; filename=Reporte_Siagie.xls');
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Document</title>
    
<style>
    .text-uppercase{
        text-transform: uppercase;
    } 

    .text-center{
        text-align: center;
    }
    
    .text-left{
        text-align: left;
    }
    
    .text-right{
        text-align: right;
    }

    .bg-gray{
        background: #CADBC0;
    }

</style>
</head>
<body>
    

<br>
<h3>REPORTE DE ALUMNOS Y APODERADOS - {{ $data->level->level_type->description }} - {{ $data->name }} </h3>
<table>
    <tr>
        <td class="text-uppercase text-left " style="text-decoration: underline" ><strong>Aula:</strong></td>                 <td class="text-left">{{ $data->name }}</td>
        <td class="text-uppercase text-left " style="text-decoration: underline" ><strong>Nivel:</strong></td>                <td class="text-left">{{ Str::lower($data->level->level_type->description) }}</td>
        <td class="text-uppercase text-left " style="text-decoration: underline" ><strong>Numero de vacantes:</strong></td>   <td class="text-left">{{ $data->vacancy }}</td>
    </tr>
    <tr>
        <td class="text-uppercase text-left" style="text-decoration: underline" ><strong>Fecha Inicio:</strong></td>         <td class="text-left">{{ Carbon\Carbon::parse($data->created_at)->format('Y/m/d') }}</td>
        <td class="text-uppercase text-left" style="text-decoration: underline" ><strong>Fecha Fin:</strong></td>            <td colspan="2" class="text-left">{{ Carbon\Carbon::parse($data->updated_at)->format('Y/m/d') }}</td>
    </tr>



</table>


<br>
<div>
    <table style="border-collapse: collapse" border="1">
        <thead>
            <tr>
                <th style="background: #CADBC0; text-transform: uppercase; " class="text-center">Aula</th>
                <th style="background: #CADBC0; text-transform: uppercase; " class="text-center">Nivel</th>
                <th style="background: #CADBC0; text-transform: uppercase; " class="text-center">DNI del alumno</th>
                <th style="background: #CADBC0; text-transform: uppercase; " class="text-center">Alumno</th>
                <th style="background: #CADBC0; text-transform: uppercase; " class="text-center">Apoderado</th>
                <th style="background: #CADBC0; text-transform: uppercase; " class="text-center">Parentesco</th>
                <th style="background: #CADBC0; text-transform: uppercase; " class="text-center">Direccion</th>
                <th style="background: #CADBC0; text-transform: uppercase; " class="text-center">Telefono del apoderado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->enrollments as $enrollment)
                <tr>
                    <td class="text-center" >{{ $data->name }}</td>
                    <td class="text-center" >{{ $data->level->level_type->description }}</td>
                    <td class="text-center" >{{ $enrollment->student->entity->document_number  }}</td>
                    <td>{{ $enrollment->student->entity->father_lastname. " ".$enrollment->student->entity->mother_lastname.", ".$enrollment->student->entity->name  }}</td>
                    <td>{{ $enrollment->relative->entity->father_lastname. " ".$enrollment->relative->entity->mother_lastname.", ".$enrollment->relative->entity->name   }}</td>
                    <td class="text-center" >
                        @switch($enrollment->relative_relationship)
                            @case('father')
                                Padre
                                @break
                            @case('mother')
                                Madre
                                @break
                                
                            @case('brother')
                                Hermano
                                @break
                                
                            @case('sister')
                                Hermana
                                @break
                                
                            @case('uncle')
                                Tio(a)
                                @break
                            @case('grandparent')
                                Abuelo(a)                                
                                @break
                            @case('cousin')
                                Primo(a)
                                @break
                            @default
                                Otro
                        @endswitch
                    </td>
                    <td class="text-center" >{{ $enrollment->relative->entity->address}}</td>
                    <td class="text-center" >{{ $enrollment->relative->entity->telephone}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    


</body>
</html>




