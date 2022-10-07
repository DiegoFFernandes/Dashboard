<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | Area do Cliente</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="{{asset('adminlte/bower_components/Ionicons/css/ionicons.min.css')}}"> --}}
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/AdminLTE.min.css') }}">
    <!-- Template Main CSS File -->
    <link href="{{ asset('css/style-login-client.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/iCheck/square/grey.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition" style="height: 100vh; background: #4a4549;">
    <div class="container-fluid container-page">
        {{-- <div class="container-login l-bg-primary">
            <div class="card">
                <h1 class="card-title">Tudo em um só lugar!</h1>
                <p>Na Área do Cliente, voce consegue notas emitidas, 2º via de boleto e muito mais...</p>
            </div>
        </div> --}}
        <div class="container-login l-bg-primary">
            <div id="carousel-ivo" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carousel-ivo" data-slide-to="0" class=""></li>
                    <li data-target="#carousel-ivo" data-slide-to="1" class="active"></li>
                    <li data-target="#carousel-ivo" data-slide-to="2" class=""></li>
                </ol>
                <div class="carousel-inner">
                    <div class="item">
                        <img src="{{ asset('img/login-folder.png') }}">
                        <div class="card">
                            <h1 class="card-title">Tudo em um só lugar!</h1>
                            <p>Na Área do Cliente, voce consegue notas emitidas, 2º via de boleto e muito mais...</p>
                        </div>
                    </div>
                    <div class="item active">
                        <img src="{{ asset('img/3.png') }}">
                        <div class="card">
                            <h1 class="card-title">Rede Ivorecap</h1>
                            <p>A maior recapadora do Brasil, venha conhecer nosso processo de produção. Marque uma
                                visita. </p>
                        </div>
                    </div>
                    <div class="item">
                        <img src="{{ asset('img/5.png') }}">
                    </div>
                </div>
                <a class="left carousel-control" href="#carousel-ivo" data-slide="prev">
                    <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-ivo" data-slide="next">
                    <span class="fa fa-angle-right"></span>
                </a>
            </div>
        </div>
        <div class="container-login l-bg-second ">
            <div class="login-box">
                <div class="login-logo">
                    <a href="../../index2.html"><b class="l-title-logo">Login</b></a>
                </div>
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <div class="message-login">
                        @if ($errors->all())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-warning" role="alert">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <p class="login-box-msg">Entre para iniciar um nova sessão</p>

                    <form action="{{ route('admin.login.do') }}" method="post">
                        @csrf
                        <div class="form-group has-feedback">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                            <span class="glyphicon glyphicon-envelope form-control-feedback bg-ivo"
                                style="color: #fff;"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback bg-ivo"
                                style="color: #fff;"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="checkbox icheck">
                                    <label>
                                        <input type="checkbox" name="remember"
                                            value="remember"><label>Lembrar-me</label>
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-xs-4">
                                <button type="submit" class="btn btn-block btn-flat bg-ivo">Entrar</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                </div>
                <!-- /.login-box-body -->
            </div>
        </div>
        <!-- /.login-box -->
    </div>

    <!-- jQuery 3 -->
    <script src="{{ asset('adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-grey',
                radioClass: 'iradio_square-grey',
                increaseArea: '20%' /* optional */
            });
        });
    </script>
</body>

</html>
