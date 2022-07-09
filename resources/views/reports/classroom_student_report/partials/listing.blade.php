<table class="table table-striped dataTables">
    <thead>
        <tr>                    
            <th>N° Documento</th>
            <th>Apellidos y Nombres</th>
            <th>Fecha Matricula</th>  
            <th>Distrito</th>
        </tr>
    </thead>
    <tbody>
    
    @foreach($students as $student)
        <tr>
            <td>
                {{$student->entity->document_number}}
            </td>

            <td>
                {{$student->entity->father_lastname}} {{$student->entity->mother_lastname}} {{$student->entity->name}} 
            </td>

            <td>
                {{date_format(date_create($student->enrollment->created_at),'d/m/Y')}}
            </td>

            <td>
                {{$student->entity->district->name}}
            </td>

           

            
        </tr>
    @endforeach
    
    </tbody>
    
    
</table>
