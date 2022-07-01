<!DOCTYPE html>
<html>

<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>ACADEMIA CABRERA | INICIAR SESIÓN</title>
        <link rel="shortcut icon" type="image/png" href="{{ asset('img/casal-favicon.png') }}" />
        <link href="{{asset('inspinia_admin-v2.9.2/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('inspinia_admin-v2.9.2/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
        <link href="{{asset('inspinia_admin-v2.9.2/css/plugins/ladda/ladda-themeless.min.css')}}" rel="stylesheet">
        <link href="{{asset('inspinia_admin-v2.9.2/css/animate.css')}}" rel="stylesheet">
        <link href="{{asset('inspinia_admin-v2.9.2/css/style.css')}}" rel="stylesheet">

</head>

<body class="gray-bg">
        <div class="loginColumns animated fadeInDown">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="font-bold" style="margin-left: 5%">ACADEMIA CABRERA</h2>
                    <img src="{{asset('images/logo-colegio-cabrera.gif')}}" style="margin-top:5%;margin-left: 20%">
                </div>
                <div class="col-md-6">
                    <div class="ibox-content" style="margin-top: 15%">
                        <form id="form" class="m-t" role="form" method="POST" action="{{ route('login') }}">
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
                            {{-- <button type="submit" class="btn btn-primary block full-width m-b">Iniciar Sesión</button> --}}
                            <button type="submit" class="ladda-button submit btn btn-primary block full-width m-b"  data-style="zoom-in">
                                Iniciar Sesión
                            </button>

                        </form>
                    </div>
                </div>
            </div>
            <!-- <hr> -->
            <div class="row">
                <div class="col-md-6">
                    <span style="margin-left: 10%;"> © ACADEMIA CABRERA {{date('Y')}} </span>    
                </div>
                <div class="col-md-6 text-right">
                    {{-- <small>© </small> --}}
                </div>
            </div>
        </div>
    </body>

    <script src="{{asset('inspinia_admin-v2.9.2/js/jquery-3.1.1.min.js') }}"></script>

    <script src="{{asset('inspinia_admin-v2.9.2/js/plugins/ladda/spin.min.js') }}"></script>
    <script src="{{asset('inspinia_admin-v2.9.2/js/plugins/ladda/ladda.min.js') }}"></script>
    {{-- <script src="{{asset('inspinia_admin-v2.9.2/js/plugins/ladda/ladda.jquery.min.js') }}"></script> --}}

    <script>
        var l = Ladda.bind('.submit');
      l.click(function(){

          // Start loading
          l.ladda( 'start' );

      });
    </script>
</html>
