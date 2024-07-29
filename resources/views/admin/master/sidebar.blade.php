<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU DE NAVEGAÇÃO</li>
            <li class="treeview {{ request()->segment(1) == 'admin' ? 'active' : '' }}" style="height: auto;">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Painel</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a
                            href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Inicio</a>
                    </li>
                    @can('ver-acesso-cliente')
                        <li class="{{ request()->routeIs('cliente.dados-gerados-empresa.index') ? 'active' : '' }}"><a
                                href="{{ route('cliente.dados-gerados-empresa.index') }}"><i class="fa fa-file-pdf-o"></i>2º
                                Via</a>
                        </li>
                    @endcan
                </ul>
            </li>
            @unlessrole('acesso-cliente')
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
                                        <a href="{{ route('producao.troca-servico') }}"><i class="fa fa-circle-o"></i>Troca
                                            de
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
            @endunlessrole
            @canany(['ver-usuario'])
                <li class="treeview {{ request()->segment(1) == 'usuario' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-user"></i> <span>Usuários</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        @role('admin')
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
                        @endrole
                        @can('ver-usuario')
                            <li class="{{ $uri == 'usuario/vincular-pessoas-usuario' ? 'active' : '' }}"><a
                                    href="{{ route('connect-people-user') }}">
                                    <i class="fa fa-plug"></i>Associar Pessoas/Usuarios</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
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
                'ver-pedidos-coletados-acompanhamento', 'ver-analise-frota'])
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
                                            <a href="{{ route('comercial.cancela-nota') }}"><i class="fa fa-ban"></i>Cancelar
                                                Nota</a>
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
                                @can('ver-analise-frota')
                                    <li class="{{ request()->routeIs('analise-frota.index') ? 'active' : '' }}">
                                        <a href="{{ route('analise-frota.index') }}">
                                            <i class="fa fa-medkit"></i>Análise de Frota
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </li>
            @endcanany
            @canany(['ver-diretoria-norte', 'ver-diretoria-sul', 'ver-diretoria-rede'])
                <li class="treeview {{ request()->segment(1) == 'diretoria' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-suitcase"></i> <span>Indicadores</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        <li class=""><a href="{{ route('diretoria.index') }}"><i
                                    class="fa fa-arrow-up"></i>Indicador</a>
                        </li>
                        {{-- @can('ver-diretoria-norte')
                            <li class="{{ request()->routeIs('diretoria.ivo-norte') ? 'active' : '' }}"><a
                                    href="{{ route('diretoria.ivo-norte') }}"><i class="fa fa-arrow-up"></i>Ivo Recap -
                                    Norte</a>
                            </li>
                        @endcan --}}
                        {{-- @can('ver-diretoria-sul')
                            <li class="{{ request()->routeIs('diretoria.ivo-sul') ? 'active' : '' }}"><a
                                    href="{{ route('diretoria.ivo-sul') }}"><i class="fa fa-arrow-down"></i>Ivo Recap -
                                    Sul</a>
                            </li>
                        @endcan --}}
                    </ul>
                </li>
            @endcanany
            @unlessrole('acesso-cliente')
                <li class="treeview {{ request()->segment(1) == 'procedimento' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-compass"></i> <span>Processos</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        <li class="treeview">
                            <a href="#"><i class="fa fa-circle-o"></i><span>Procedimento</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @canany(['ver-procedimento'])
                                    <li class="{{ $uri == 'procedimento/index' ? 'active' : '' }}"><a
                                            href="{{ route('procedimento.index') }}">
                                            <i class="fa fa-lock"></i>Movimentações</a>
                                    </li>
                                @endcanany
                                <li class="{{ $uri == 'procedimento-aprovador/autorizador' ? 'active' : '' }}"><a
                                        href="{{ route('procedimento.autorizador') }}">
                                        <i class="fa fa-thumbs-o-up"></i>Avaliar</a>
                                </li>
                                <li class="{{ $uri == 'procedimento/publicos' ? 'active' : '' }}"><a
                                        href="{{ route('procedimento.publish') }}">
                                        <i class="fa fa-book"></i>Publicos</a>
                                </li>
                            </ul>
                        </li>
                        @canany(['ver-sgi'])
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i><span>SGI</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    @role(['admin|sgi'])
                                        <li class="{{ $uri == 'sgi/index' ? 'active' : '' }}"><a
                                                href="{{ route('sgi.index') }}">
                                                <i class="fa fa-lock"></i>Movimentações</a>
                                        </li>
                                    @endrole
                                    <li class="{{ $uri == 'sgi/publicos' ? 'active' : '' }}"><a
                                            href="{{ route('sgi.publish') }}">
                                            <i class="fa fa-book"></i>Publicos</a>
                                    </li>
                                </ul>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endunlessrole
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
            @canany(['ver-comercial-sul', 'ver-diretoria-sul', 'ver-producao', 'ver-diretoria-rede'])
                <li class="treeview" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-industry"></i> <span>Estoque</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        @can('ver-producao')
                            <li class="active"><a href="{{ route('estoque.index') }}"><i class="fa fa-archive"></i>
                                    Lote Estoque</a>
                            </li>
                        @endcan
                        @canany(['ver-comercial-sul', 'ver-diretoria-sul', 'ver-diretoria-rede'])
                            <li class="active"><a href="{{ route('estoque.saldo-estoque') }}"><i
                                        class="fa fa-balance-scale"></i>Saldo Estoque</a>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany
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
            @role('admin|controladoria')
                <li class="treeview {{ request()->segment(1) == 'junsoft' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-arrow-down"></i> <span>Junsoft</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        <li class="{{ request()->routeIs('importa.index') ? 'active' : '' }}"><a
                                href="{{ route('importa.index') }}"><i class="fa fa-scissors"></i>Importar P/ Portal
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('item-centro-resultado.index') ? 'active' : '' }}"><a
                                href="{{ route('item-centro-resultado.index') }}"><i class="fa fa-scissors"></i>Centro de Resultado
                            </a>
                        </li>
                        @can('ver-gerenciador-contabil')
                            <li class="{{ request()->routeIs('parm-contabilidade.index') ? 'active' : '' }}"><a
                                    href="{{ route('parm-contabilidade.index') }}"><i class="fa fa-scissors"></i>Gerenciador
                                    Contabilidade
                                </a>
                            @endcan
                        </li>
                    </ul>
                </li>
            @endrole
            @canany(['ver-manutencao'])
                <li class="treeview {{ request()->segment(1) == 'manutencao' ? 'active' : '' }}" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span>Manutenção</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        <li class="{{ request()->routeIs('manutencao.index') ? 'active' : '' }}"><a
                                href="{{ route('manutencao.index') }}"><i class="fa fa-ticket "></i>Chamados</a>
                        </li>

                        <li class="{{ request()->routeIs('manutencao.machines') ? 'active' : '' }}"><a
                                href="{{ route('manutencao.machines') }}"><i class="fa fa-puzzle-piece"></i>Cadastro de
                                Maquinas</a>
                        </li>
                        <li class="{{ request()->routeIs('manutencao-report') ? 'active' : '' }}"><a
                                href="{{ route('manutencao-report') }}"><i class="fa fa-flag"></i>Relatório</a>
                        </li>

                    </ul>
                </li>
            @endcanany
            @unlessrole('acesso-cliente')
                <li class="header">Links Publicos</li>
                <li class="treeview" style="height: auto;">
                    <a href="#">
                        <i class="fa fa-circle-o text-yellow"></i><span>Aquila</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">
                        <li class="treeview">
                            <a href="#"><i class="fa fa-line-chart"></i> Indicadores
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @canany(['ver-diretoria-norte', 'ver-diretoria-sul'])
                                    <li class="treeview">
                                        <a href="#"><i class="fa fa-circle-o"></i>Diretoria
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                        </a>
                                        <ul class="treeview-menu">
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:x:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/ES-q_nxYzM5JmiJZ-Xu6DZ0BgzU3W1lutDSv3TdAy28b4g?e=T30aEG"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>N1 - Diretoria</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endcanany
                                @canany(['ver-financeiro', 'ver-faturamento', 'ver-cobranca', 'ver-recursos-humanos'])
                                    <li class="treeview">
                                        <a href="#"><i class="fa fa-circle-o"></i>Suporte
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                        </a>
                                        <ul class="treeview-menu">
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:x:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EbzOjGTDZIFFrPpZ1kLFgpkB-sR0q-d7SjmeH-wWz0OMWw?e=4nDru8"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>N2 - Administrativo</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:x:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EeMQInoqjkpDtDaylykMZ_8BQPk1rNsicCMYmzd00_H6Sw?e=tjc4Mr"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>N3 - Administrativo</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:x:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EY-9-0_gkJdEnRVQait1NscBvpk0oyCStkdCbZjTm3JWMg?e=2BtGhw"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>N3 - Controladoria</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:x:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/Edo6Y5_x0Q1HuZ-qVi1mdNsBXBzJlMk9A8N4er3M7Yi73Q?e=Jr8GDb"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>N3 - Gestão Financeira</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:x:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EV7ilh-R4xNJiAmhXFyb_VkBbuWu27U06dIdmj31VKSrjw?e=DiiW0F"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>N3 - T.I Suporte</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endcanany
                                @canany(['ver-producao'])
                                    <li class="treeview">
                                        <a href="#"><i class="fa fa-circle-o"></i>Produto
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                        </a>
                                        <ul class="treeview-menu">
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:x:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EUXAsbNwuuBLoiG1fS50feABcCorAPeTu4lyBgl4NucDRQ?e=Z2Fde4"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>N2 - Operações</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endcanany
                                @canany(['ver-comercial-norte', 'ver-comercial-sul'])
                                    <li class="treeview">
                                        <a href="#"><i class="fa fa-circle-o"></i>Comercial
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                        </a>
                                        <ul class="treeview-menu">
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EkoR6O9ShalMg7n_zXD3KlwBONhN4UnnMjjyNJHuFwm7Dg?e=RKRMl8"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>N2 - Comercial</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endcanany
                                @role('admin|coordenador|gerencia')
                                    <li class="treeview">
                                        <a href="#"><i class="fa fa-circle-o"></i>Gestão de Unidade
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                        </a>
                                        <ul class="treeview-menu">
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EnvQd5J0IrNOn0OqTxwq85QBcfC2DqEUMEkz1YlRSYMQJg?e=OobLsP"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Alto Paraná</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EmVfLSenLtFIu2uWb9nmvQYBsqKlrIEkmQ5G5Ks1-M95zg?e=pJSN3e"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Assis</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EhPatQ9dnK1JtEIdoT7sjeIB3nNA5VEhkSCNBYwuwuxPug?e=RFtu4i"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Bauru</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/Eudb18YPTk9ApZFAEohL7oUBZPXXKpkkJ2hSNIku0zAR_A?e=58d4hW"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Campina Grande Do Sul</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/Eg20RFsMN51JtR6jvSHJ9uMBN_f8LHCRiId-t_7RCn7YSQ?e=dSUsfP"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Campo Largo</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/Eq9u42k95c9HsD-VF0nKVDkBaoS6aDQsMwkH1fNmZ1cA-A?e=rKBcmm"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Campo Largo - Renovat</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EsEhRZdsYYRKpbxwLJuo4SwBLlFN-2cvKg9UoNjjYNzTxA?e=0nT99L"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Dourados</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/En7ATiBH_mRAm98qNULbGd8BdtQokUCOaJSfT0g91gykoA?e=PLy7mc"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Itapeva</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/EsxZO4RB5SJPoCPTQyxocFoBHkW3nl8I7XT314uc06VJ5g?e=nDaZA7"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Paranavai</a>
                                            </li>
                                            <li class="">
                                                <a href="https://ivorecap-my.sharepoint.com/:f:/g/personal/qualidadepvai2_ivorecap_onmicrosoft_com/En15XSUjMN5BhPcGtDQNUugB7nuHKN67bJOwvZti_36DDA?e=ylP0eu"
                                                    target="_blank">
                                                    <i class="fa fa-circle-o"></i>Vitorino</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endrole
                            </ul>
                        </li>
                    </ul>
                </li>
            @endunlessrole
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
