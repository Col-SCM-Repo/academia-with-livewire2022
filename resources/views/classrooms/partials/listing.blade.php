<table class="table">
    <thead>
        <th>Nombre</th>
        <th>Total Vancantes</th>
        <th></th>
    </thead>
    <tbody>
    @foreach ($classrooms as $classroom)
        <tr>
            <td>{{$classroom->name}}</td>
            <td>{{$classroom->vacancy}}</td>
            <td> <a  onclick="delete_classroom({{$classroom->id}})"> <i class="fa fa-times"></i> </a> </td>
        </tr>
    @endforeach
        
    </tbody>
</table>