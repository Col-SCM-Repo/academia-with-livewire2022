<table class="table table-striped dataTables">
    <thead>
        <tr>                    
            <th>Usuario</th>
            <th>Monto</th>
        </tr>
    </thead>
    <tbody>
    
    @foreach($users as $key => $user)
        <tr>
            <td>
                {{$user->entity->name}} {{$user->entity->father_lastname}}
            </td>

            <td>
                S/ {{ number_format($collections[$key], 2, '.', ' ') }}
            </td>

           

            
        </tr>
    @endforeach
    
    </tbody>
    
    
</table>
