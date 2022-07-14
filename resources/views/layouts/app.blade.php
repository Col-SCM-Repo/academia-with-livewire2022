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

        @livewireStyles
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

        <!-- Flot -->
        <script src="{{ asset('inspinia_admin/js/plugins/flot/jquery.flot.js') }}"></script>
        <script src="{{ asset('inspinia_admin/js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
        <script src="{{ asset('inspinia_admin/js/plugins/flot/jquery.flot.spline.js') }}"></script>
        <script src="{{ asset('inspinia_admin/js/plugins/flot/jquery.flot.resize.js') }}"></script>
        <script src="{{ asset('inspinia_admin/js/plugins/flot/jquery.flot.pie.js') }}"></script>

        <!-- Peity -->
        <script src="{{ asset('inspinia_admin/js/plugins/peity/jquery.peity.min.js') }}"></script>
        <script src="{{ asset('inspinia_admin/js/demo/peity-demo.js') }}"></script>

        <!-- Custom and plugin javascript -->
        <script src="{{ asset('inspinia_admin/js/inspinia.js') }}"></script>
        <script src="{{ asset('inspinia_admin/js/plugins/pace/pace.min.js') }}"></script>

        <!-- jQuery UI -->
        <script src="{{ asset('inspinia_admin/js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

        <!-- GITTER -->
        <script src="{{ asset('inspinia_admin/js/plugins/gritter/jquery.gritter.min.js') }}"></script>

        <!-- Toastr -->
        <script src="{{ asset('inspinia_admin/js/plugins/toastr/toastr.min.js') }}"></script>

        <!-- Application (laravel) -->
        <script src="{{ asset('js/app.js') }}"></script>

        {{-- <script>
            $(document).ready(function() {
                setTimeout(function() {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        showMethod: 'slideDown',
                        timeOut: 4000
                    };
                    toastr.success('Responsive Admin Theme', 'Welcome to INSPINIA');

                }, 1300);
            });
        </script> --}}

        <script>
            $(document).ready(function () {

                $('.sidebar-collapse').slimscroll({
                    height: '100%',
                    railOpacity: 0.9
                });
            });
        </script>

        @stack('scripts')
        @livewireScripts

        <!------ Alerts ------>
        <script>
            Livewire.on('alert-sucess', (informacion)=>{
                toastr.success(informacion.mensaje, informacion.titulo);
            });

            Livewire.on('alert-danger', (informacion)=>{
                toastr.success(informacion.mensaje, informacion.titulo);
            });
            
            Livewire.on('alert-warning', (informacion)=>{
                toastr.warning(informacion.mensaje, informacion.titulo);
            });

            Livewire.on('change-modal', (modal) => {
                $(modal.query).modal(modal.estado);
            });
            

        </script>

    </body>
</html>






















