<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/logo-icon.png" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link href="{{ Asset('css/signin.css') }}" rel="stylesheet">
</head>

<body class="text-center">
    <div class="container-fluid">
        <div class="row padding">
            <form class="form-signin" action="{{route('admin.login.do')}}" method="post">
                @csrf
                <img class="mb-4" src="{{ Asset('img/logo-ivorecap.png') }}" alt="" width="120" height="100">
                <h1 class="h3 mb-3 font-weight-normal">Entrar</h1>
                <div class="form-group">
                    <label for="login" class="sr-only">Usuário</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Usuário" required
                        autofocus>
                </div>
                <div class="form-group">
                    <label for="password" class="sr-only">Senha</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Senha"
                        required>
                </div>
                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" name="remember" value="remember" checked> Lembrar de mim
                    </label>
                </div>
                <button class="btn btn-lg btn-ivo btn-block" type="submit">Acessar</button>
                <div class="message-login">
                    @if($errors->all())
                    @foreach($errors->all() as $error)
                    <div class="alert alert-warning" role="alert">
                        {{$error}}
                    </div>
                    @endforeach
                    @endif
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>