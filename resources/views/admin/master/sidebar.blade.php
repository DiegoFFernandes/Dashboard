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
                    <li class="active"><a href="{{ route('admin.dashborad') }}"><i class="fa fa-home"></i>Inicio</a>
                    </li>
                </ul>
            </li>
            @role('admin|producao')
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-th"></i> <span>Produção</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li>
                        <a href="{{ route('admin.producao.etapas') }}"><i class="fa fa-circle-o"></i>Produção por
                            Etapa</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.lote.pcp') }}"><i class="fa fa-circle-o"></i>Lote PCP</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.producao.acompanha.ordem') }}"><i class="fa fa-circle-o"></i>Acompanhar
                            Ordem</a>
                    </li>
                    <li>
                    <li><a href=""><i class="fa fa-circle-o"></i>Status Expedição</a>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Produtividade
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.producao.quadrante1') }}"><i
                                        class="fa fa-circle-o"></i>Quadrante
                                    1</a></li>
                            <li><a href="{{ route('admin.producao.quadrante2') }}"><i
                                        class="fa fa-circle-o"></i>Quadrante
                                    2</a></li>
                            <li><a href="{{ route('admin.producao.quadrante3') }}"><i
                                        class="fa fa-circle-o"></i>Quadrante
                                    3</a></li>
                            <li><a href="{{ route('admin.producao.quadrante4') }}"><i
                                        class="fa fa-circle-o"></i>Quadrante
                                    4</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endrole
            @role('admin')
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Usuários</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="{{ $uri == 'usuarios/cadastrar' ? 'active' : '' }}"><a
                            href="{{ route('admin.usuarios.create') }}">
                            <i class="fa fa-plus-circle"></i>Adicionar usuário</a>
                    </li>
                    <li class="{{ $uri == 'usuarios/listar' ? 'active' : '' }}"><a
                            href="{{ route('admin.usuarios.listar') }}">
                            <i class="fa fa-list"></i>Listar usuários</a>
                    </li>
                    <li class="{{ $uri == 'usuarios/funcao' ? 'active' : '' }}"><a
                            href="{{ route('admin.usuarios.role') }}">
                            <i class="fa fa-shield"></i>Funções</a>
                    </li>
                    <li class="{{ $uri == 'usuarios/permissao' ? 'active' : '' }}"><a
                            href="{{ route('admin.usuarios.permission') }}">
                            <i class="fa fa-lock"></i>Permissões</a>
                    </li>
                </ul>
            </li>
            @endrole
            @role('admin')
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Pessoas</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i>Cadastros
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li >
                                <a href="{{ route('pessoa.index') }}"><i class="fa fa-plus-circle"></i>Pessoa</a>
                            </li>
                            <li>
                                <a href="{{ route('email.index') }}"><i class="fa fa-plus-circle"></i>Email</a>
                            </li>
                        </ul>
                    </li>                    
                </ul>                
            </li>
            @endrole
            @role('portaria|admin')
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-shield"></i> <span>Portaria</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i>Veiculos
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i> Cadastros
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a href="{{ route('marca-veiculo') }}">
                                            <i class="fa fa-circle-o"></i>Marcas
                                        </a>
                                    </li>
                                    <li><a href="{{ route('modelo-veiculo') }}"><i
                                                class="fa fa-circle-o"></i>Modelos</a></li>
                                    <li><a href="{{ route('marca-modelo.index') }}"><i
                                                class="fa fa-circle-o"></i>Marca/Modelos</a></li>
                                    <li>
                                        <a href="{{ route('listar.motorista.veiculos') }}">
                                            <i class="fa fa-circle-o"></i>Motorista/Veiculos
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Movimentos
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">                            
                            <li><a href="{{ route('admin.portaria.entrada') }}"><i
                                        class="fa fa-plus-circle"></i>Entrada</a></li>
                            <li><a href="{{ route('admin.portaria.saida') }}"><i
                                        class="fa fa-plus-circle"></i>Saida</a>
                            </li>
                        </ul>
                    </li>
                    <li><a href=""><i class="fa fa-plus-circle"></i>Relátorios</a>
                    </li>
                </ul>
            </li>
            @endrole
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-th"></i> <span>Comercial</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="active"><a href="{{ route('comercial.index') }}"><i class="fa fa-home"></i>Acesso</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
