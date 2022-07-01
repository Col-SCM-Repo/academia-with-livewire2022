<html class="loading" lang="en" data-textdirection="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

        <title>DESCUBRE</title>

        <link rel="apple-touch-icon" href="{{asset('chameleon/images/ico/apple-icon-120.png')}}">
        <link rel="shortcut icon" type="image/x-icon" href="">
        <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
        <link href="{{ asset('fontawesome-free-5.2.0/css/all.min.css') }}" rel="stylesheet">
        <!-- BEGIN VENDOR CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('chameleon/css/vendors.min.css')}}">
        {{-- <link rel="stylesheet" type="text/css" href="{{asset('chameleon/vendors/css/forms/toggle/switchery.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('chameleon/css/plugins/forms/switch.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('chameleon/css/core/colors/palette-switch.min.css')}}"> --}}
        <!-- END VENDOR CSS-->
        <!-- BEGIN CHAMELEON  CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('chameleon/css/app.min.css')}}">
        <!-- END CHAMELEON  CSS-->
        <!-- BEGIN Page Level CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('chameleon/css/core/menu/menu-types/horizontal-menu.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('chameleon/css/core/colors/palette-gradient.min.css')}}">
        <!-- END Page Level CSS-->
        <!-- BEGIN Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{asset('chameleon/assets/css/style.css')}}">
        <!-- END Custom CSS-->

        <style>
            div.form-control-position{
                margin-top: 1em;
            }

             input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;
            }
        </style>


        @yield('styles')
    </head>
    <!-- BODY -->
    <body class="horizontal-layout horizontal-menu 2-columns   menu-expanded" data-open="hover" data-menu="horizontal-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">

        <!-- NAVBAR -->
        @include('chameleon_partials.top_nav')
        <!--/ NAVBAR -->

        <!-- NAVIGATION - MENU -->

            @include('chameleon_partials.nav_menu')


        <!--/ NAVIGATION - MENU -->

        <!-- CONTENT -->
        <div class="app-content content">
            <div class="content-wrapper">
                <div class="content-wrapper-before">

                </div>
                <div class="content-header row">

                    @yield('header')

                </div>
                <div class="content-body">
                    @yield('content')
                </div>
            </div>

        </div>
        <!--/ CONTENT -->

        {{-- include('partials.customizer'); --}}

        @include('chameleon_partials.footer')

        <!-- BEGIN VENDOR JS-->
        <script src="{{asset('chameleon/vendors/js/vendors.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('chameleon/vendors/js/forms/toggle/switchery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('chameleon/js/scripts/forms/switch.min.js')}}" type="text/javascript"></script>
        <!-- BEGIN VENDOR JS-->
        <!-- BEGIN PAGE VENDOR JS-->
        <script type="text/javascript" src="{{asset('chameleon/vendors/js/ui/jquery.sticky.js')}}"></script>
        <!-- END PAGE VENDOR JS-->
        <!-- BEGIN CHAMELEON  JS-->
        <script src="{{asset('chameleon/js/core/app-menu.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('chameleon/js/core/app.min.js')}}" type="text/javascript"></script>
        {{-- <script src="{{asset('chameleon/js/scripts/customizer.min.js')}}" type="text/javascript"></script> --}}
        <script src="{{asset('chameleon/vendors/js/jquery.sharrre.js')}}" type="text/javascript"></script>
        <!-- END CHAMELEON  JS-->
        <!-- BEGIN PAGE LEVEL JS-->
        <!-- END PAGE LEVEL JS-->



        @yield('scripts')

    </body>
    <!--/ BODY -->
</html>
