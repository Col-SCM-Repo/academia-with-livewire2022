<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ACADEMIA | Main</title>
    <link href="{{ asset('inspinia_admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <!-- Toastr style -->
    <link href="{{ asset('inspinia_admin/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <!-- Gritter -->
    <link href="{{ asset('inspinia_admin/js/plugins/gritter/jquery.gritter.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin/css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('inspinia_admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <style>

        *{
            font-size: 12px;
        }
        .modal-backdrop {
            background: #00000085;
        }
        input[type=text] {
            text-transform: uppercase
        }

        /* Inicio : Agregar a estilos globales */
        .d-block {
            display: block;
            width: 100%
        }

        .d-inline {
            display: block !important;
        }

        .d-inlineblock {
            display: inline-block !important;
        }



        .p-0{
            padding: 0 !important;
        }

        .px-0{
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .py-0{
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .d-inline {
            display: inline !important
        }


        /* fin : Agregar a estilos globales */

    </style>
    @stack('styles')
</head>

<body>
    <div id="wrapper">
        @include('layouts.partials.side_nav_app')
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                @include('layouts.partials.top_nav_app')
            </div>
            <div class="row  border-bottom white-bg dashboard-header">
                @yield('header')
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper wrapper-content">
                        @yield('content')
                        {{ $slot }}
                    </div>
                    @include('layouts.partials.footer')
                </div>
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('inspinia_admin/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('inspinia_admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Peity -->
 {{--    <script src="{{ asset('inspinia_admin/js/plugins/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin/js/demo/peity-demo.js') }}"></script> --}}

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('inspinia_admin/js/inspinia.js') }}"></script>

    <script src="{{ asset('inspinia_admin/js/plugins/toastr/toastr.min.js') }}"></script>



</body>

</html>