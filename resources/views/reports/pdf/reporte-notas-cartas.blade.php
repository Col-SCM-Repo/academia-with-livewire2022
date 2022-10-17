<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $nombre_documento }}</title>

    <style>
        *{
            padding: 0;
            border: 0;
            box-sizing: border-box;
            font-size: 11px;
            font-family: Helvetica
        }

        .table-notas th, .table-notas td {
            border: 1px solid #000;
            background: rebeccapurple
        }
        th, td{
            padding-left: 4px;
            padding-right: 4px;
        }
        h1{
            font-size: 1.125em;
            font-family: Helvetica
           /*  text-align: center;
            color: #2874A6; */

        }
        p, span, u, strong{
            font-size: 1rem;
            font-family: Helvetica
        }
        td{
            font-size: .9rem;
        }
        th{
            font-size: .9rem;
        }


        .documento-pdf{
            margin: 2rem;
            margin-top: 3rem;
        }
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
        .text-justify{
            text-align: justify;
        }
        .text-bold{
            font-weight: 900;
        }
        .text-underline{
            text-decoration: underline;
        }
        .text-uppercase{
            text-transform: uppercase;
        }
        .table-notas{
            width: 85%;
            margin: 0 auto;
            border-collapse: collapse;
            border: 1px solid black;
        }
        .text-red{
            color: #FF0000;
        }

    </style>

</head>
<body>
    @if ( $matriculas && count($matriculas)>0 )
        @foreach ($matriculas as $index=>$matricula)
            @php
                $estudiante = $matricula->student;
                $apoderados = $estudiante->relative;

                $nombreEstudiante = $estudiante->entity->father_lastname.' '.$estudiante->entity->mother_lastname.', '.$estudiante->entity->name;
                $nombreApoderado = 'APODERADO';

                if(count($apoderados)>0)
                    $nombreApoderado = $apoderados[0]->apoderado->father_lastname.' '.$apoderados[0]->apoderado->mother_lastname.', '.$apoderados[0]->apoderado->name;
            @endphp
            <div class="documento-pdf" >
                <table style="width: 100%; margin: 25rem auto">
                    <tr>
                        <td>
                            <h1 class="text-uppercase text-center" style="font-size: 1.25rem"> {{ $nombreApoderado }}  </h1> <br><br>
                            <h1 class="text-uppercase text-center" style="font-size: 1.25rem"> {{ $nombreEstudiante }}  </h1>
                        </td>
                        <td style="width: 200px">
                            <h1 class="text-uppercase text-center" style="font-size: 1.25rem">  </h1> <br><br>
                            <h1 class="text-uppercase text-center" style="font-size: 1.25rem"> {{ $matricula->classroom->name }}  </h1>
                        </td>
                    </tr>
                </table>

            </div>
            @if ( count($matriculas)-1 != $index ) <div style="page-break-after:always;"></div> @endif
        @endforeach
    @else
        <div >
            No se encontró respuestas para el examen
            <hr>
        </div>
    @endif
</body>
</html>
