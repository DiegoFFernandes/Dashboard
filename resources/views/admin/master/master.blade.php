<!DOCTYPE html>
<html lang="pt-br">

@includeIf('admin.master.head')

<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
    <div class="wrapper">
        @includeIf('admin.master.header')
        @includeIf('admin.master.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    {{isset($title_page) ? $title_page : 'Dashboard'}}
                    @if(isset($uri))
                    <small>{{$uri}}</small>
                    @else
                    <small>Painel de Controle</small>
                    @endif
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">
                        @if(isset($uriAtual))
                        {{$uriAtual}}
                        @else Dashboard
                        @endif
                    </li>
                </ol>
            </section>
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        @includeIf('admin.master.footer')
    </div>
    <!-- ./wrapper -->
    @includeIf('admin.master.scripts')

</body>

</html>