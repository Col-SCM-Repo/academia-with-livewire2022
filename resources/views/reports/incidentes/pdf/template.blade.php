<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('titulo', 'Colegio cabrera')</title>
    <style>
        *{
            font-size: 16px;
            margin: 0 auto;
            text-align: center;
            padding: 2px;
            
        }
        body{
            padding: 0.625em;
            font-family: sans-serif;
        }
        header{
            margin: 1.25em;
        }
        th{
            padding: 1px 2px;
            font-size: 0.75em;
        }
        td{
            padding: 1px 2px;
            font-size: 0.75em;
        }
        table {
           border-collapse: collapse;
           width: 100%;
           margin: 1em;
        }
        footer{
            width: 70%;
            position: fixed; 
            border:  2px solid #030303; 
            bottom: 3cm; 
            left: 2.5cm; 
            font-size: 0.875em;
            text-align: center;
        }
        h1{
            font-size: 1.25em;
            padding: 1em;
            
        }
        h3{
            font-size: 1em;
            padding: 1em;            
        }
        .col-4{
            width: 25%;
        }
        
        .background_shadow{
            background: #EEF0F2;
        }
        tr .background_shadow{
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
    @yield('styles')
</head>
<body>
    @include('reports.incidentes.pdf.partials.header')
    <div class="container">
        @yield('content')
    </div>
    @include('reports.incidentes.pdf.partials.footer')
</body>
</html>

