<table class="table table-striped dataTables">
    <thead>
        <tr>                    
            <th style="text-align: center">Nivel</th>
            <th style="text-align: center">N° Matriculados</th>
        </tr>
    </thead>
    <tbody>

    <?php
        $total=0;
    ?>
    
    @foreach($period->levels as $key => $level)
        <tr>
            <td style="font-weight: bold;text-align: center" colspan="2">
                {{$level->level_type->description}} 
            </td>
        </tr>
        @foreach($level->classrooms as $key => $classroom)
            <tr>
                <td style="text-align: center">
                    {{$classroom->name}} 
                </td>

                <td style="text-align: center">
                    {{ $classroom->enrollments->count() }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;text-align: center">
                TOTAL
            </td>

            <td style="font-weight: bold;text-align: center">
                {{ $level->enrollments->count() }}
            </td>
        </tr>
        <?php

            $total+=$level->enrollments->count()

        ?>
    @endforeach
    
    </tbody>

    <tfoot>
        <tr>    
                 

            <th style="font-weight: bold;text-align: center">
                <strong>TOTAL ALUMNOS MATRICULADOS</strong>
            </th>
        
            <th style="font-weight: bold;text-align: center">
                
                {{$total}}

            </th>
        
        </tr>
    </tfoot>
    
    
</table>
