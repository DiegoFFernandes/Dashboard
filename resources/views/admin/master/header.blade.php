<header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>IVO</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>IVO RECAP</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            @if(isset($user_auth->name))
            <ul class="nav navbar-nav">
                @role('portaria|admin')
                <li class="">
                    <a href="{{route('admin.portaria.entrada')}}">
                        Entrada
                        <span class="label label-success">{{$qtdEntrada}}</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{route('admin.portaria.saida')}}">
                        Saida
                        <span class="label label-warning">{{$qtdSaida}}</span>
                    </a>
                </li>
                @endrole
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('adminlte/dist/img/avatar5.png')}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">
                            {{$user_auth->name}}
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('adminlte/dist/img/avatar5.png')}}" class="img-circle" alt="User Image">
                            <p>
                                {{$user_auth->name}}
                                <small>Membro há {{$user_auth->created_at->diffForHumans()}}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('profile-user')}}" class="btn btn-default btn-flat">Perfil</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('admin.logout')}}" class="btn btn-default btn-flat">Sair</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif
        </div>
    </nav>
</header>