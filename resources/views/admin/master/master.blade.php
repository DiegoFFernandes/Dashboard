<!DOCTYPE html>
<html lang="pt-br">

@includeIf('admin.master.head')

<body class="skin-blue sidebar-mini sidebar-collapse" id="body">
    <div class="wrapper">
        @includeIf('admin.master.header')
        @includeIf('admin.master.sidebar')
        @includeIf('admin.master.control-sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1 id="title-page">
                    {{ isset($title_page) ? $title_page : 'Portal' }}
                    @if (isset($uri))
                        <small>{{ $uri }}</small>
                    @else
                        <small>Painel de Controle</small>
                    @endif
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">
                        @if (isset($uriAtual))
                            {{ $uriAtual }}
                        @else
                            Portal
                        @endif
                    </li>
                </ol>
            </section>
            @yield('content')
            <div class="alert alert-success alert-fixed hidden" id="alert-msg">
                <p></p>
            </div>
            {{-- Icon loading --}}
            <div class="hidden" id="loading">
                <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
            </div>
        </div>
        <!-- /.content-wrapper -->
        @includeIf('admin.master.footer')
    </div>

    <!-- ./wrapper -->
    @includeIf('admin.master.scripts')

    @yield('scripts')
    <script>
        $('#control-sidebar-collapsed').change(function(){
            if(this.checked){
                $("#body").removeClass('sidebar-collapse');
            }else{
                $("#body").addClass('sidebar-collapse');
            }
        });
    </script>
</body>

</html>
