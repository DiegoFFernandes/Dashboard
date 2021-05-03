<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU DE NAVEGAÇÃO</li>
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="active"><a href="{{route('admin.dashborad')}}"><i class="fa fa-home"></i>Inicio</a>
                    </li>
                    <li class="active"><a href=""><i class="fa fa-circle-o"></i>Status Expedição</a>
                    </li>
                </ul>
            </li>
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-th"></i> <span>Produção</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li>
                        <a href="{{route('admin.producao.etapas')}}"><i class="fa fa-circle-o"></i>Produção por
                            Etapa</a>
                    </li>
                    <li>
                        <a href="{{route('admin.lote.pcp')}}"><i class="fa fa-circle-o"></i>Lote PCP</a>
                    </li>
                    <li>
                        <a href="{{route('admin.producao.acompanha.ordem')}}"><i class="fa fa-circle-o"></i>Acompanhar
                            Ordem</a>
                    </li>
                    <li>
                        <a href="{{route('admin.producao.produtividade')}}"><i class="fa fa-circle-o"></i>Produtividade</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>