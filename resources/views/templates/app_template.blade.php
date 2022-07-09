<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SIGEL | @yield('title')</title>

    <link href="{{ asset('inspinia_admin-v2.9.2/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <link href="{{ asset('fontawesome-5.9.0/css/all.min.css') }}" rel="stylesheet">

    

    <link href="{{ asset('inspinia_admin-v2.9.2/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin-v2.9.2/css/style.css') }}" rel="stylesheet">

    @yield('styles')

</head>

{{-- <body class="fixed-sidebar pace-done fixed-nav fixed-nav-basic"> --}}
<body class="md-skin fixed-nav no-skin-config  fixed-sidebar pace-done">
    <div id="wrapper">
        @include('inspinia_partials.side_nav_app')
        <div id="page-wrapper" class="gray-bg" style="min-height: 394px;">
            <div class="row border-bottom">
                @include('inspinia_partials.top_nav_app')
            </div>
            @yield('heading')
            <div class="wrapper wrapper-content">
                @yield('content')
            </div>
            @include('inspinia_partials.footer')
        </div>
        @include('inspinia_partials.side_conf')
    </div>



    <!-- Mainly scripts -->
    <script src="{{ asset('inspinia_admin-v2.9.2/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('inspinia_admin-v2.9.2/js/inspinia.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/slimscroll/jquery.slimscroll.js') }}"></script>

    <script>

        $(document).ready(function () {

            $('.sidebar-collapse').slimscroll({
	            height: '100%',
	            railOpacity: 0.9
            });

        });

    </script>

    @yield('scripts')

</body>

</html>
