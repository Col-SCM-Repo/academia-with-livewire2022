<div class="ibox ">

   

    <div class="ibox-title">
        <h5>Resultados de Busqueda</h5>
    </div>

    @if ($enrollments->isEmpty())
        <div class="ibox-content">
            <strong>NO SE HA ENCONTRADO MATRICULAS RELACIONADAS CON EL PARAMETRO DE BÚSQUEDA.</strong>
        </div>
    @else
                          
    @foreach ($enrollments as $enrollment)
    
    <div class="ibox-content">

        

        <div class="row">
            <div class="col-4">
                <small class="stats-label">Alumno</small>
                <h4>{{$enrollment->student->entity->document_number}} - {{strtoupper($enrollment->student->entity->full_name())}}</h4>
            </div>

            <div class="col-2">
                <small class="stats-label">Fecha Termino</small>
                <h4>{{date_format(date_create($enrollment->classroom->level->end_date),'d/m/Y')}}</h4>
                
            </div>

            <div class="col-3">
                <small class="stats-label">Ciclo/Nivel</small>
                <br>
                <p style="font-size: 0.9em;margin-top: 2%;">{{$enrollment->classroom->level->period->name}}/{{$enrollment->classroom->level->level_type->description}}</p>
            </div>
            
            <div class="col-1">
                <small class="stats-label">Pago</small>
                <h4>    
                    
                    @if ($enrollment->payment_status_descripion() == 'pending')
                        <span class="label label-danger">Con Deuda</span>
                    @endif

                    @if ($enrollment->payment_status_descripion() == 'finished')
                        <span class="label label-primary">Total Pagado</span>
                    @endif
                </h4>
            </div>
            
            <div class="col-1">
                <small class="stats-label">Estado</small>
                <h4>
                    <?php
                        $status_description=$enrollment->status_descripion();
                    ?>
                    @if ($status_description == 'Vigente')
                        <span class="label label-primary">{{$status_description}}</span>
                    @endif

                    @if ($status_description == 'Concluida')
                        <span class="label">{{$status_description}}</span>
                    @endif

                    @if ($status_description == 'Retirado')
                        <span class="label label-danger">{{$status_description}}</span>
                    @endif
                </h4>                   
            </div>

            <div class="col-1">
                <small class="stats-label"></small><br>
                <a href="{{action('EnrollmentController@edit',['id'=>$enrollment->id])}}" target="_blank" style="font-size:1.7em;color: #347AB7"> <i class="fa fa-cog"></i>  </a>
            </div>
        </div>
    </div>

    @endforeach

    @endif
                            
                            
</div>