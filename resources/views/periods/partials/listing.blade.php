<table class="table table-striped dataTables">
    <thead>
        <tr>                    
            <th>Nombre</th>
            <th>Año</th>
            <th>Estado</th>                                             
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    @foreach($periods as $period)
        <tr>
            <td>
                {{$period->name}} 
            </td>
            <td>
                {{$period->year}}
            </td>
            <td>
                @if($period->active==1)
                <i class="fa fa-check" style="font-size: 15px;color: #1b881b;"></i> Vigente
                @endif

                @if($period->active==0)
                <i class="fas fa-long-arrow-alt-down" style="font-size: 15px;color: #af3030;"></i> Inhabilitado
                @endif
            </td>
            <td>
                
                <div class="dropdown">
                    
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="margin-left: 10px;font-size: 15px;">
                        <i class="fa fa-cog"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="{{action('PeriodController@edit',[$period->id])}}"><i class="fas fa-pen"></i> Editar </a>
                        </li>
                        @if($period->active==0)
                        <li>   
                            <a href="#" onclick="status({{$period->id}},1)" style="color: #1b881b;"><i class="fas fa-check"></i> Habilitar </a>
                        </li>
                        @endif

                        @if($period->active==1)
                        <li>   
                            <a href="#" onclick="status({{$period->id}},0)" style="color: #af3030;"><i class="fas fa-long-arrow-alt-down"></i> Inhabilitar </a>
                        </li>
                        @endif
                        
                    </ul>
                </div>
                
            </td>
        </tr>
    @endforeach
    </tbody>
</table>