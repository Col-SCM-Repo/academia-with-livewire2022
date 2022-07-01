<br>
<br>
<div class="row col-12  ">
    <div class="p-1 col-md-1">
        <strong>Aula:</strong> {{ $data->name }}
    </div>
    <div class="p-1 col-md-2 col-6 col-sm-5    text-capitalize">
        <strong>Nivel:</strong> {{ Str::lower($data->level->level_type->description) }}
    </div>
    <div class="p-1 col-md-3 col-6 col-sm-5   ">
        <strong>Numero de vacantes:</strong> {{ $data->vacancy }}
    </div>
    <div class="p-1 col-md-2 col-6 col-sm-5   ">
        <strong>Fecha Inicio:</strong> {{ Carbon\Carbon::parse($data->created_at)->format('Y/m/d') }}
    </div>
    <div class="p-1 col-md-2 col-6 col-sm-5   ">
        <strong>Fecha Fin:</strong> {{ Carbon\Carbon::parse($data->updated_at)->format('Y/m/d') }}
    </div>
    <div class="col-md-2 col-12 col-sm-3  mt-4 mt-sm-0 text-right">
        <a href="{{ action('ReportController@show_alumnos_y_apoderados', ['id'=>$data->id, 'descargar'=>"descargar"]) }}" class="btn btn-sm btn-primary"> 
            <i class="fa fa-download" aria-hidden="true"></i>    
            Descargar
        </a>
    </div>
</div>
<br>
<div class="p-1 bg-white">

    <table  class="table table-striped table-sm">
        <thead class="bg-info">
            <tr c>
                <th class="text-uppercase text-center">DNI del alumno</th>
                <th class="text-uppercase text-center">Alumno</th>
                <th class="text-uppercase text-center">Apoderado</th>
                <th class="text-uppercase text-center">Parentesco</th>
                <th class="text-uppercase text-center">Direccion</th>
                <th class="text-uppercase text-center">Telefono del apoderado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->enrollments as $enrollment)
                <tr>
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
    






