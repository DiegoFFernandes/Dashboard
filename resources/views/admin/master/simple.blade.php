<!DOCTYPE html>
<html lang="pt-br">

@section('style')
    <style>
        * {
            overflow: visible !important;
        }

        .page-break {
            page-break-after: always;
        }

        .imagem-inline img {
            padding-left: 15px;
            display: inline-block;
            margin-right: 10px;
            width: 320px;
            height: 240px;
        }

        .logo-section {
            margin: 10px 0 20px 0;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }

        .logo-section img {
            height: 100px;
            width: auto;
            float: left;
            margin-right: 14px;
            margin-top: -39px;
        }
    </style>
@endsection

@includeIf('admin.master.head')

<body onload="init()">
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="" style="min-height: 346px;">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                {!! isset($title_page) ? '<h1 id="title-page">' . $title_page . '</h1>' : '' !!}
            </section>
            @yield('content')

        </div>

    </div>

    <!-- ./wrapper -->
    @includeIf('admin.master.scripts')

    @yield('scripts')

</body>

</html>
