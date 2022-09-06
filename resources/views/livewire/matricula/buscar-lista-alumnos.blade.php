<div>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>ESTUDIANTES REGISTRADOS</h5>
            {{-- <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#">Config option 1</a>
                    </li>
                    <li><a href="#">Config option 2</a>
                    </li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div> --}}
        </div>
        <div class="ibox-content">
            <form>
                <div class="form-group">
                    <input type="text" class="form-control" wire:model='busqueda' placeholder="Buscar alumno">
                </div>
                <div>
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th scope="col" >Cod.</th>
                                <th scope="col" >Apellidos y nombres</th>
                            </tr>
                        </thead>
                        <tbody style="cursor: pointer;">
                            @if ( count($alumnos) > 0)
                                @foreach ($alumnos as $alumno)
                                    <tr>
                                        <td scope="row">{{ $alumno->id }}</td>
                                        <td>{{ $alumno->father_lastname.' '.$alumno->mother_lastname.', '.$alumno->name }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center" > <small>Sin resultados</small> </td>
                                    </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

</div>
