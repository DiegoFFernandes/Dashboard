<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU DE NAVEGAÇÃO</li>
            <li class="treeview {{ request()->segment(1) == 'admin' ? 'active' : '' }}" style="height: auto;">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="{{ request()->routeIs('admin.dashborad') ? 'active' : '' }}"><a
                            href="{{ route('admin.dashborad') }}"><i class="fa fa-home"></i>Inicio</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ request()->segment(1) == 'producao' ? 'active' : '' }}" style="height: auto;">
                <a href="#">
                    <i class="fa fa-th"></i> <span>Produção</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Movimentações
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ request()->segment(2) == 'acompanha-ordem' ? 'active' : '' }}">
                                <a href="{{ route('admin.producao.acompanha.ordem') }}">
                                    <i class="fa fa-circle-o"></i>Acompanhar Ordem</a>
                            </li>
                            @role('admin|producao')
                                <li>
                                    <a href="{{ route('admin.producao.etapas') }}"><i class="fa fa-circle-o"></i>Produção
                                        por
                                        Etapa</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.lote.pcp') }}"><i class="fa fa-circle-o"></i>Lote PCP</a>
                                </li>
                                <li>
                                <li>
                                    <a href=""><i class="fa fa-circle-o"></i>Status Expedição</a>
                                </li>
                                <li>
                                    <a href="{{ route('producao.troca-servico') }}"><i class="fa fa-circle-o"></i>Troca de
                                        Serviço</a>
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
                            @endrole
                            @can('ver-controle-epi')
                                <li class="{{ request()->routeIs('epis.index') ? 'active' : '' }}">
                                    <a href="{{ route('epis.index') }}"><i class="fa fa-circle-o"></i>Controle Epi</a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                    @canany(['ver-controle-epi'])
                        <li class="treeview">
                            <a href="#"><i class="fa fa-circle-o"></i> Bridgestone
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="{{ request()->routeIs('gqc-pneus-faturados-marca') ? 'active' : '' }}">
                                    <a href="{{ route('gqc-pneus-faturados-marca') }}"><i class="fa fa-circle-o"></i>GQC
                                        Brigdestone</a>
                                </li>
                                <li class="{{ request()->routeIs('api-new-age.index') ? 'active' : '' }}">
                                    <a href="{{ route('api-new-age.index') }}">
                                        <i class="fa fa-circle-o"></i>Garantia Ouro
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcanany
                </ul>
            </li>
            @role('admin')
                <li class="treeview {{ request()->segment(1) == 'usuario' ? 'active' : '' }}" style="height: auto;">
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
                                <li>
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
                        <li><a href="{{ route('portaria.movimentos') }}"><i class="fa fa-plus-circle"></i>Relátorios</a>
                        </li>
                    </ul>
                </li>
            @endrole
            @canany(['ver-comercial-norte', 'ver-comercial-sul', 'ver-rel-cobranca-sul',
                'ver-pedidos-coletados-acompanhamento'])
                <li class="treeview {{ request()->segment(1) == 'comercial' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-map"></i> <span>Comercial</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        @role('admin')
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i><span>Cadastros</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{ $uri == 'usuario/regiao-comercial' ? 'active' : '' }}"><a
                                            href="{{ route('regiao-comercial.index') }}">
                                            <i class="fa fa-lock"></i>Região Comercial</a>
                                    </li>
                                    <li class="{{ $uri == 'usuario/area-comercial' ? 'active' : '' }}"><a
                                            href="{{ route('area-comercial.index') }}">
                                            <i class="fa fa-lock"></i>Area Comercial</a>
                                    </li>
                                </ul>
                            </li>
                        @endrole
                        <li class="treeview {{ request()->segment(2) == 'movimento' ? 'active' : '' }}">
                            <a href="#"><i class="fa fa-circle-o"></i> Movimentos
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @can('ver-comercial-norte')
                                    <li class="{{ request()->routeIs('comercial.ivo-norte') ? 'active' : '' }}"><a
                                            href="{{ route('comercial.ivo-norte') }}"><i class="fa fa-arrow-up"></i>Ivo
                                            Recap -
                                            Norte</a>
                                    </li>
                                @endcan
                                @can('ver-comercial-sul')
                                    <li class="{{ request()->routeIs('comercial.ivo-sul') ? 'active' : '' }}"><a
                                            href="{{ route('comercial.ivo-sul') }}"><i class="fa fa-arrow-down"></i>Ivo
                                            Recap -
                                            Sul</a>
                                    </li>
                                    @can('ver-cancela-nota')
                                        <li class="{{ request()->routeIs('comercial.cancela-nota') ? 'active' : '' }}">
                                            <a href="{{ route('comercial.cancela-nota') }}"><i
                                                    class="fa fa-ban"></i>Cancelar Nota</a>
                                        </li>
                                    @endcan
                                    @role('controladoria|admin')
                                        <li class="{{ request()->routeIs('comercial.list-nota-all') ? 'active' : '' }}">
                                            <a href="{{ route('comercial.list-nota-all') }}">
                                                <i class="fa fa-list"></i>Notas a cancelar
                                            </a>
                                        </li>
                                    @endrole
                                @endcan
                                @can('ver-rel-cobranca-sul')
                                    <li class="{{ request()->routeIs('comercial.rel-cobranca-sul') ? 'active' : '' }}"><a
                                            href="{{ route('comercial.rel-cobranca-sul') }}"><i
                                                class="fa fa-ban"></i>Relatório
                                            de cobranca</a></li>
                                @endcan
                                @can('ver-pedidos-coletados-acompanhamento')
                                    <li class="{{ request()->routeIs('acompanha-pedidos') ? 'active' : '' }}"><a
                                            href="{{ route('bloqueio-pedidos') }}"><i class="fa fa-hand-paper-o"></i>
                                            Acompanhamento Pedidos
                                        </a></li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </li>
            @endcanany
            @canany(['ver-diretoria-norte', 'ver-diretoria-sul'])
                <li class="treeview {{ request()->segment(1) == 'diretoria' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-suitcase"></i> <span>Diretoria</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        @can('ver-diretoria-norte')
                            <li class="{{ request()->routeIs('diretoria.ivo-norte') ? 'active' : '' }}"><a
                                    href="{{ route('diretoria.ivo-norte') }}"><i class="fa fa-arrow-up"></i>Ivo Recap -
                                    Norte</a>
                            </li>
                        @endcan
                        @can('ver-diretoria-sul')
                            <li class="{{ request()->routeIs('diretoria.ivo-sul') ? 'active' : '' }}"><a
                                    href="{{ route('diretoria.ivo-sul') }}"><i class="fa fa-arrow-down"></i>Ivo Recap -
                                    Sul</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @role('cobranca|admin')
                <li class="treeview {{ request()->segment(1) == 'cobranca' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-credit-card"></i> <span>Cobranca</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        <li class="{{ request()->routeIs('cobranca.index') ? 'active' : '' }}"><a
                                href="{{ route('cobranca.index') }}"><i class="fa fa-address-book-o"></i>Agenda</a>
                        </li>
                        <li class="{{ request()->routeIs('search-envio') ? 'active' : '' }}"><a
                                href="{{ route('search-envio') }}"><i class="fa fa-search"></i>Consulta Envio
                                Nfe/Boleto</a>
                        </li>
                    </ul>
                </li>
            @endrole
            @role('admin|producao')
                <li class="treeview" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-industry"></i> <span>Estoque</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        <li class="active"><a href="{{ route('importa.index') }}"><i
                                    class="fa fa-archive"></i>Importa Item Junsoft</a>
                        </li>
                        <li class="active"><a href="{{ route('estoque.index') }}"><i
                                    class="fa fa-archive"></i>Criar Lote Estoque</a>
                        </li>
                    </ul>
                </li>
            @endrole
            @role('admin|financeiro')
                <li class="treeview {{ request()->segment(1) == 'financeiro' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-money"></i> <span>Financeiro</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        <li class="{{ request()->routeIs('cobranca.index') ? 'active' : '' }}"><a
                                href="{{ route('financeiro.index') }}"><i class="fa fa-file-excel-o"></i>Conciliação
                                Empresas</a>
                        </li>
                    </ul>
                </li>
            @endrole
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-question-circle"></i> <span>Suporte</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class=""><a href="https://glpi.ivorecap.com.br" target="_blank"><i
                                class="fa fa-ticket" aria-hidden="true"></i>GLPI</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
