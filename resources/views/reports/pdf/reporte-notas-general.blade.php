<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        *{
            padding: 0;
            border: 0;
            box-sizing: border-box;
            font-size: 10px;
        }
        table{
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
            padding: .125rem;
        }
        h1{
            font-size: 1.5rem;
            text-transform: uppercase;
            text-align: center
        }
        .text-center{
            text-align: center;
        }
    </style>

</head>
<body>
    <h1> RESULTADOS DE EXAMEN {{ $examen->name }} - GRUPO {{ $examen->group->description }} </h1>
    <table style="border-collapse: collapse;">
        <thead>
            <tr>
                <th class="text-center" > MERITO </th>
                <th class="text-center" > APELLIDOS Y NOMBRES </th>
                <th class="text-center" > P. CORRECTAS </th>
                <th class="text-center" > P. INCORRECTAS </th>
                <th class="text-center" > P. BLANCO </th>
                <th class="text-center" > PUNTAJE </th>
            </tr>
        </thead>
        @foreach ($examen->exam_summaries as $index=>$resumenExamen )
            <tr>
                <td class="text-center" > {{ $index+1 }} </td>
                <td style="padding-left: .75rem;"> {{ $resumenExamen->surname. ', '.$resumenExamen->name }} </td>
                <td class="text-center" > {{ $resumenExamen->correct_answer }} </td>
                <td class="text-center" > {{ $resumenExamen->wrong_answer }} </td>
                <td class="text-center" > {{ $resumenExamen->blank_answer }} </td>
                <td class="text-center" > {{ $resumenExamen->final_score }} </td>
            </tr>
        @endforeach
    </table>
    <div style="margin-top: 1rem; text-align: right;">
        <small style="font-size: 1rem;"> Fecha de generación {{ date( 'Y/m/d g:i:s a' ) }} </small>
    </div>
</body>
</html>
