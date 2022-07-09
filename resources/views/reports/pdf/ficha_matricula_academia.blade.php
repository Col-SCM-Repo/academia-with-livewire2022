<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Ficha  de Matricula</title>
    <style>
        *{
            font-family: Arial, Helvetica, sans-serif;
            padding: 0;
            margin: 0;
            box-sizing: border-box  ;
            font-size: 14px;
        }
        .color-blue{
            color: #01579baa
        }

        .h1-title{
            text-align: center;
            color: #01579baa;
            font-size: 1.5em;
            font-weight: 900;
        }
        
        td{
            font-size: .7em;
            padding-bottom: 1rem;
        }
        /* labels */
        .table-geneeral td{
            padding: 1PX 5px;
            padding-bottom: 3px;
        }

        .table-geneeral td p{
            background: #7bb6d499;
            padding: 1px 5px;
        }

        .table-geneeral, .table-compromiso-pago, .info{
            margin: 1rem auto ;
            width: 90%;
        }

        .table-compromiso-pago th{
            background: #7bb6d499   ;
        }

        .table-text-label, .table-compromiso-pago th{
            font-size: 1em;
            font-weight: 400;
        }
        
        .table-general-cabecera{
            padding: 2px 1em;
            color: #fff;
            background: #01579baa;
            font-size: .9em;
            letter-spacing: 2px;
        }

        .table-compromiso-pago th{
            font-size: .7em;
        }

        .info{
            margin: 2rem;
            padding: 1rem;
            background: #01579baa ;
        }
        .info span{
            background: #fff;
            text-align: center;
            padding: .5em 1em;
            color: #ff6600;
            display: block;
            border-radius: 8px;
            font-weight: 700;
            font-size: .7em;
        }

        .text-uppercase{
            text-transform: uppercase;
        }

        .text-center{
            text-align: center;
        }

    </style>
</head>
<body>
    <br>
    <br>
    <h1 class="color-blue h1-title">FICHA DE MATRICULA</h1>

    <table class="table-geneeral" >
        <tr> <th colspan="7" class="table-general-cabecera"> DATOS GENERALES </th></tr>
        <tr>
            <td style="width: 70px;"><p class="table-text-label">MATRICULA: </p>  </td>    
            <td> 
                {{$enrollment->code}} 
            </td>
            <td style="width: 40px;"><p class="table-text-label">PAGO: </p>  </td>         
            <td>
                {{ substr(str_repeat(0, 8).$enrollment->installments[0]->id, - 8)  }}
            </td>
            <td style="width: 60px;"><p class="table-text-label">ID CICLO:</p>   </td>     
            <td> 
                {{Str::substr($enrollment->classroom->level->level_type->description, 0,6)}}_{{$enrollment->classroom->level->period->year}}
            </td>
            <td rowspan="3" style="width: 70px; padding: 0; padding-right: 9px" > 
                <img style="border: 1px solid #0064a2;width: 100%;" src="{{ asset('uploads/photo_files/'.$enrollment->student->photo_file) }}" >
            </td>
        </tr>
        <tr>
            <td> <p class="table-text-label"> PROGRAMA: </p>  </td>     
            <td colspan="3"> 
                {{$enrollment->career->career}} </td>
            <td> <p class="table-text-label"> FECHA: </p>  
            </td>        
            <td> 
                {!! \Carbon\Carbon::parse($enrollment->classroom->level->start_date)->format('d/m/Y') !!} 
            </td>
        </tr>
        <tr>
            <td> <p class="table-text-label"> NIVEL: </p>  </td>
            <td>
                {{$enrollment->classroom->level->level_type->description}}
            </td>
            <td> <p class="table-text-label"> GRUPO: </p>  </td>    
            <td> {{$enrollment->career->group->description }} </td>
            <td> <p class="table-text-label"> AULA: </p>   </td>    
            <td> 
                {{$enrollment->classroom->name}} 
            </td>
        </tr>
    </table>

    <table class="table-geneeral" >
        <tr> <th colspan="7" class="table-general-cabecera"> DATOS DEL ALUMNO </th></tr>
        <tr>
            <td colspan="1"> <p  class="table-text-label">APELLIDOS Y NOMBRES: </p>   </td> 
            <td colspan="4" class="text-uppercase"> 
                {{$enrollment->student->entity->father_lastname}} {{$enrollment->student->entity->mother_lastname}} {{$enrollment->student->entity->father_lastname}}
            </td>
            <td> <p class="table-text-label"> D.N.I: </p>   </td> <td> {{$enrollment->student->entity->document_number}} </td>
        </tr>
        <tr>
            <td> <p class="table-text-label">DIRECCIÓN: </p>   </td> 
            <td colspan="4" class="text-uppercase"> 
                {{$enrollment->student->entity->address}} 
            </td> 
            <td style="width: 100px;"> <p class="table-text-label">AÑO DE EGRESO: </p>  </td> <td style=""> {{$enrollment->student->graduation_year}} </td>
        </tr>
        <tr>
            <td> <p class="table-text-label">I.E. DE PROCEDENCIA: </p>   
            </td> <td colspan="4" class="text-uppercase">
                {{$enrollment->student->school->name}}
            </td>
            <td style="width: 50px"> <p class="table-text-label">LUGAR: </p>  </td> 
            <td style="width: 157px;">
                {{$enrollment->student->entity->district->name}}
            </td>
        </tr>
    </table>

    <!---------------------- Datos del Apoderado ----------------------> 
    <table class="table-geneeral" >
        <tr>
            <th colspan="7" class="table-general-cabecera">DATOS DEL APODERADO</th>
        </tr>
        <tr>
            <td colspan="2" style="width: 120px;"> <p  class="table-text-label">APELLIDOS Y NOMBRES: </p>   </td> 
            <td colspan="3" class="text-uppercase">
                {{$enrollment->relative->entity->father_lastname}}
                {{$enrollment->relative->entity->mother_lastname}}
                {{$enrollment->relative->entity->name}}
            </td>
            <td colspan='1' style="width: 50px;"> <p class="table-text-label" > D.N.I: </p>   </td>
            <td colspan='1' style="width: 206px;">
                {{$enrollment->relative->entity->document_number}}
            </td>
        </tr>
        <tr>
            <td style="width: 80px;"> <p class="table-text-label"> DIRECCIÓN: </p>    </td> 
            <td colspan="6" class="text-uppercase">
                {{$enrollment->relative->entity->address}}
            </td> 
            
        </tr>
        <tr>
            <td> <p class="table-text-label"> Nº CELULAR: </p>    </td> 
            <td colspan="6">
                {{$enrollment->relative->entity->telephone}}
                {{$enrollment->relative->entity->mobile_phone? " | ".$enrollment->relative->entity->mobile_phone: "" }}
            </td>
        </tr>

    </table>
    
    <!------------------------------ Begin: Table Compromiso de pago ------------------------------>
    <table class="table-geneeral" >
        <tr><th colspan="7" class="table-general-cabecera">COMPROMISO DE PAGO</th></tr>
        <tr>
            <td colspan="7">
                <table class="table-compromiso-pago">
                    <thead>
                        <tr>
                            <th>CONCEPTO</th>
                            <th>MONTO</th>
                            <th>FECHA DE PAGO</th>
                            <th>FECHA DE VENCIMIENTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrollment->installments as $pago )
                            <tr>
                                <td class="text-center" >
                                    @if ($pago->type=="enrollment")
                                        MATRICULA
                                    @else
                                        ACTA {{(int)$pago->order}}º CUOTA {{Str::substr($enrollment->classroom->level->level_type->description, 0,6)}} 
                                        @endif
                                    </td>
                                    <td class="text-center" style="font-size: .7rem">
                                    S./ {{$pago->amount}}
                                </td>
                                <td class="text-center">
                                    @if ($pago->type=="enrollment")
                                        {{count($enrollment->installments[0]->payments)>0? $enrollment->installments[0]->payments[0]->created_at : "-" }}
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>                
                <!------------------------------ End: Table Compromiso de pago ------------------------------>
            </td>    
        </tr>
    </table>

    <!------------------------------ Table Observaciones ------------------------------>
    <table class="table-geneeral" >
        <tr><th colspan="7" class="table-general-cabecera">OBSERVACIONES</th></tr>
        <tr>
            <td colspan="7" style="font-size: .9rem;">
                {{$enrollment->observations}}
            </td>
        </tr>
    </table>
    <div class="bg-blue info">
        <span>
                UNA VEZ FIRMADO EN EL PRESENTE COMPROMISO DE MATRICULA NO HABRÁ DEVOLUCION 
                DEL DINERO <br> POR NINGUN MOTIVO
        </span>
    </div>

    <br>
    
    <table style="width: 90%;  margin: 0 auto;" >
        <tr>
            <td>
                <img src="{{ asset('images/partials-report/huella.png') }}" style="width: 90px;">
            </td>

            <td style="width: 80%;">
                <span style="font-size: .75rem; text-transform: uppercase">
                    Usuario: <small style="text-transform: capitalize; font-size: .8rem">{{ Str::lower($enrollment->user->entity->name.' '.$enrollment->user->entity->father_lastname) }}</small>
                </span>
                <br>
                <br>
                <br>
                <br>
                <p style="font-size: .75rem; ">................................................</p>
                <p style="font-size: .75rem; ">  Firma</p>
            </td>
        </tr>
    </table>

    
    
</body>
</html>

