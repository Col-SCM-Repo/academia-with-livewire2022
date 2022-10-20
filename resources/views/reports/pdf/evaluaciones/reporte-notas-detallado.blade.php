<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $nombreExamen }}</title>

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

            <div class="documento-pdf">

                @if ( $matricula->examenes && count($matricula->examenes)>0  )
                    @foreach ($matricula->examenes as $j => $examenResumen)

                        <h1>SR(A):</h1>
                        <p class="text-justify">Mediante la presente le expresamos nuestro cordial saludo, al mismo tiempo le hacemos llegar la información referente al
                            RENDIMIENTO ACADÉMICO de su hijo(a): <strong>{{ $matricula->nombre_estudiante  }}.</strong> </p>
                        <h1 class="text-center"> <u> SIMULACRO DE EXAMEN DE ADMISIÓN </u> </h1>

                        <p> <strong>  FECHA: </strong> {{ date('d/m/Y', strtotime($examenResumen->exam->exam_date)) }}  <br>
                            <strong>  VALOR TOTAL SIMULACRO: </strong> {{ $examenResumen->exam->maximun_score }}  <br>
                            <strong>  NRO. PREGUNTAS: </strong> {{ $examenResumen->exam->number_questions }}  </p>

                        <h1 class="text-underline" >REFERENTE AL ALUMNO:</h1>
                        <h1 class="text-right"> PUNTAJE TOTAL OBTENIDO: <span class="text-red"> {{ $examenResumen->final_score }} </span> </h1>

                        <table class="table-notas">
                            <thead>
                                <tr>
                                    <th>NRO</th>
                                    <th>NRO. <br> PREG.</th>
                                    <th>PUNTAJE  <br> POR PREG. </th>
                                    <th>CURSO</th>
                                    <th>CORRECTAS</th>
                                    <th>INCORRECTAS</th>
                                    <th>EN <br> BLANCO</th>
                                    <th>NOTA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ( count($examenResumen->course_summary)>0 )
                                    @foreach ($examenResumen->course_summary as $i=>$cursoResumen)
                                        <tr>
                                            <td class="text-center"> {{ $i+1 }} </td>
                                            <td class="text-center"> {{ $cursoResumen->correct_answers + $cursoResumen->wrong_answers + $cursoResumen->blank_answers }} </td>
                                            <td class="text-center"> {{ $cursoResumen->score_question  }} </td>
                                            <td> {{ $cursoResumen->course->name }} </td>
                                            <td class="text-center"> {{ $cursoResumen->correct_answers }} </td>
                                            <td class="text-center"> {{ $cursoResumen->wrong_answers }} </td>
                                            <td class="text-center"> {{ $cursoResumen->blank_answers }} </td>
                                            <td class="text-center"> {{ $cursoResumen->correct_score - $cursoResumen->wrong_score }} </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="padding: 1rem;" class="text-center"> No se encontrarón respuestas </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <br>

                        <h1 class="text-underline" >NOTA IMPORTANTE:</h1>
                        <p class="text-justify">
                            El Simulacro es calificado según la cantidad de preguntas por asignatura, cada curso tiene un valor de <strong> 20 </strong>
                            puntos que se divide entre la cantidad de preguntas asignadas por materia, mismas que se detallan en el cuadro de la parte superior, y en su
                            totalidad el simulacro suma <strong>{{ $examenResumen->exam->maximun_score }} </strong>  puntos, las respuestas incorrectas y/o preguntas no contestadas no tienen valor.
                        </p>
                        <br>
                        <p class="text-center">Atentamente.</p>
                        <p class="text-right"> Departamento de Evaluación y Control Academia CABRERA </p>

                        @if ( count($matricula->examenes)-1 != $j ) <div style="page-break-after:always;"></div> @endif
                    @endforeach

                @else
                    @if ($matricula->mostrarTodos)
                        <h1>SR(A):</h1>
                        <p class="text-justify">Mediante la presente le expresamos nuestro cordial saludo, al mismo tiempo le hacemos llegar la información referente al
                            RENDIMIENTO ACADÉMICO de su hijo(a): <strong>{{ $matricula->nombre_estudiante  }}.</strong> </p>
                        <h1 class="text-center"> <u> SIMULACRO DE EXAMEN DE ADMISIÓN </u> </h1>
                        <p> <strong>  FECHA: </strong> - <br>
                            <strong>  VALOR TOTAL SIMULACRO: </strong> -  <br>
                            <strong>  NRO. PREGUNTAS: </strong> -
                        </p>
                        <h1 class="text-underline" >REFERENTE AL ALUMNO:</h1>
                        <h1 class="text-right"> PUNTAJE TOTAL OBTENIDO: <span class="text-red"> - </span> </h1>
                        <table class="table-notas">
                            <thead>
                                <tr>
                                    <th>NRO</th>
                                    <th>NRO. <br> PREG.</th>
                                    <th>PUNTAJE  <br> POR PREG. </th>
                                    <th>CURSO</th>
                                    <th>CORRECTAS</th>
                                    <th>INCORRECTAS</th>
                                    <th>EN <br> BLANCO</th>
                                    <th>NOTA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" style="padding: 1rem;" class="text-center"> El alumno no realizó el examen </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <h1 class="text-underline" >NOTA IMPORTANTE:</h1>
                        <p class="text-justify">
                            El Simulacro es calificado según la cantidad de preguntas por asignatura, cada curso tiene un valor de <strong> - </strong>
                            puntos que se divide entre la cantidad de preguntas asignadas por materia, mismas que se detallan en el cuadro de la parte superior, y en su
                            totalidad el simulacro suma <strong> - </strong>  puntos, las respuestas incorrectas y/o preguntas no contestadas no tienen valor.
                        </p>
                        <br>
                        <p class="text-center">Atentamente.</p>
                        <p class="text-right"> Departamento de Evaluación y Control Academia CABRERA </p>

                        @if ( count($matriculas)-1 != $index ) <div style="page-break-after:always;"></div> @endif
                    @endif
                @endif
            </div>
        @endforeach
    @else
        <div >
            No se encontró alumnos
        </div>
    @endif
</body>
</html>
