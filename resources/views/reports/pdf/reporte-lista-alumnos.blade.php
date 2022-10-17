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

        /* th, td{
            padding-left: 4px;
            padding-right: 4px;
        } */
        h1{
            font-size: 1.255rem;
            font-family: Helvetica;
            text-align: center;
            /* color: #2874A6; */
            text-decoration: underline;
            text-transform: uppercase;
        }

        th{
            background: #2c2c2c52;
        }
        td, th{
            padding: .45rem 1rem ;
        }


        .documento-pdf{
            margin: 2rem;
            margin-top: 3rem;
        }
        table, td, tr, th{
            border: 1px solid black;
            border-collapse: collapse;
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

        .text-red{
            color: #FF0000;
        }

    </style>

</head>
<body>
    @if ( $matriculas && count($matriculas)>0 )
        <div class="documento-pdf" >
            <h1> {{ $nombre_documento }}</h1>
            <table style="width: 100%; margin: 1rem auto" >
                <thead class="thead-inverse">
                    <tr>
                        <th class="text-center" >Nª</th>
                        <th class="text-center" >APELLIDOS Y NOMBRES</th>
                        <th class="text-center" >DNI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($matriculas && count($matriculas)>0 )
                    @foreach ($matriculas as $index=>$matricula)
                                @php
                                    $entidad = $matricula->student->entity;
                                    @endphp
                                <tr>
                                    <td class="text-center" scope="row"> {{ $index+1 }} </td>
                                    <td>{{ $entidad->father_lastname.' '.$entidad->mother_lastname.', '.$entidad->name }} </td>
                                    <td class="text-center">{{ $entidad->document_number }} </td>
                                </tr>
                            @endforeach
                        @else
                            <tr> <td colspan="3" style="padding: 3rem;"> NO SE ENCONTRÓ ALUMNOS</td> </tr>
                        @endif
                    </tbody>
            </table>
        </div>
    @else
        <div >
            No se encontró respuestas para el examen
            <hr>
        </div>
    @endif
</body>
</html>
