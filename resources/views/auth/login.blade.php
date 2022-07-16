<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>ACADEMIA CABRERA | INICIAR SESIÓN</title>
        <link rel="shortcut icon" type="image/png" href="{{ asset('img/casal-favicon.png') }}" />
        <link href="{{asset('inspinia_admin/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('inspinia_admin/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
        <link href="{{asset('inspinia_admin/css/plugins/ladda/ladda-themeless.min.css')}}" rel="stylesheet">
        <link href="{{asset('inspinia_admin/css/animate.css')}}" rel="stylesheet">
        <link href="{{asset('inspinia_admin/css/style.css')}}" rel="stylesheet">
    </head>

    <body class="gray-bg">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div>
                <div>
                    <h1 class="logo-name" style="padding-bottom: 3rem">
                        <img src="{{asset('images/logo-colegio-cabrera.gif')}}" style="margin: 0 auto; width: 60%; ">
                    </h1>
                </div>
                <h3>Bienvenido al sistema Academia </h3>
                <p> Sistema para la gestiòn academica del nivel Preuniversitario</p> <br>
                <p>Ingreso al sistema.</p>
                <form class="m-t" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('username') || $errors->has('password') ? ' has-error' : '' }}">
                        <input type="text" name="username" class="form-control" placeholder="Usuario" required="">
                    </div>
                    <div class="form-group{{ $errors->has('username') || $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" required="">
                        @if ($errors->has('username') || $errors->has('password'))
                            <span class="help-block">
                                <strong style="color: crimson">Las credenciales usadas no son correctas</strong>
                            </span>
                        @endif
                    </div>
                    <button type="submit" class="ladda-button submit btn btn-primary block full-width m-b"  data-style="zoom-in">
                        Iniciar Sesión
                    </button>
                    <p class="text-muted text-center"><small>Si olvido su contraseña, contactar con el administrador.</small></p>
                </form>
                <p class="m-t"> <small> © ACADEMIA CABRERA  &copy; {{date('Y')}}</small> </p>
            </div>
        </div>
        <!-- Mainly scripts -->
        <script src="{{asset('inspinia_admin/js/jquery-3.1.1.min.js') }}"></script>
        <script src="{{asset('inspinia_admin/js/bootstrap.min.js') }}"></script>
        <script src="{{asset('inspinia_admin/js/plugins/ladda/spin.min.js') }}"></script>
        <script src="{{asset('inspinia_admin/js/plugins/ladda/ladda.min.js') }}"></script>
        <script>
            var l = Ladda.bind('.submit');
            l.click(function(){
                l.ladda( 'start' );
            });
        </script>
    </body>
</html>



