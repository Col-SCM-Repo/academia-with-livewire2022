<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>ACADEMIA CABRERA | @yield('title')</title>
    
    <link href="{{ asset('inspinia_admin-v2.9.2/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-5.9.0/css/all.min.css') }}" rel="stylesheet">
    @yield('styles-inter')
    <link href="{{ asset('inspinia_admin-v2.9.2/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('inspinia_admin-v2.9.2/css/style.css') }}" rel="stylesheet">
    <link href="{{asset('inspinia_admin-v2.9.2/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">
    
    @yield('styles')

</head>


<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom white-bg">


                
                    @include('inspinia_partials.nav_main')
                    




            </div>
            @yield('heading')
            <div class="wrapper wrapper-content">
                @yield('content')
            </div>
            @include('inspinia_partials.footer')
        </div>
    </div>



    <!-- Mainly scripts -->
    <script src="{{ asset('inspinia_admin-v2.9.2/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/popper.min.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/bootstrap.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{asset('inspinia_admin-v2.9.2/js/plugins/toastr/toastr.min.js')}}"></script>

    @yield('scripts-inter')
    <!-- Custom and plugin javascript -->
    <script src="{{ asset('inspinia_admin-v2.9.2/js/inspinia.js') }}"></script>
    <script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/pace/pace.min.js') }}"></script>

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