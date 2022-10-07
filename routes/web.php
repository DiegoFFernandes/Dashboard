<?php

use App\Http\Controllers\Admin\Cobranca\BloqueioPedidosController;
use App\Http\Controllers\Admin\Producao\AcompanhaOrdemController;
use App\Http\Controllers\Admin\Cobranca\CobrancaController;
use App\Http\Controllers\Admin\Cobranca\InadimplenciaController;
use App\Http\Controllers\Admin\Cobranca\RelatorioCobrancaController;
use App\Http\Controllers\Admin\Comercial\AreaComercialController;
use App\Http\Controllers\Admin\Comercial\CancelarNotaController;
use App\Http\Controllers\Admin\Comercial\ComercialController;
use App\Http\Controllers\Admin\Comercial\RegiaoComercialController;
use App\Http\Controllers\Admin\Comercial\VendedoresController;
use App\Http\Controllers\Admin\Email\EmailController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\Estoque\ItemLoteEntradaEstoqueController;
use App\Http\Controllers\Admin\Estoque\LoteEntradaEstoqueController;
use App\Http\Controllers\Admin\Financeiro\FinanceiroController;
use App\Http\Controllers\Admin\LotePcpController;
use App\Http\Controllers\Admin\MarcaModeloFrotaController;
use App\Http\Controllers\Admin\MarcaVeiculoController;
use App\Http\Controllers\Admin\ModeloVeiculoController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Pessoa\PessoaController;
use App\Http\Controllers\Admin\PneusLotePcpController;
use App\Http\Controllers\Admin\PortariaController;
use App\Http\Controllers\Admin\Producao\ApiNewAgeController;
use App\Http\Controllers\Admin\Producao\ExecutorController;
use App\Http\Controllers\Admin\Producao\GqcBridgestoneController;
use App\Http\Controllers\admin\Producao\MetaOperadorController;
use App\Http\Controllers\Admin\ProducaoEtapaController;
use App\Http\Controllers\admin\ProdutividadeController;
use App\Http\Controllers\Admin\Produto\ImportaItemJunsoftController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VeiculoController;
use App\Http\Controllers\admin\WebHook\WebHookController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PowerBi\PowerBiEmbeddedController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear-cache-all', function () {

    Artisan::call('cache:clear');

    dd("Cache Clear All");
});

/* Rotas de Login Client */
Route::get('/login-cliente', [LoginController::class, 'showLoginClientForm'])->name('login-client');


/* Rotas de Login */
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login/do', [LoginController::class, 'Login'])->name('admin.login.do');
Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('/painel', [LoginController::class, 'dashboard'])->name('admin.dashborad');

Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa.index');
Route::get('webhook', [WebHookController::class, 'index'])->name('webhook');

Route::middleware(['auth'])->group(function () {
    /**Rota Perfil usuario*/
    Route::get('perfil-usuario', [UserController::class, 'profileUser'])->name('profile-user');
    Route::post('perfil-usuario/do', [UserController::class, 'updateProfileUser'])->name('profile-user.update');
});
Route::middleware(['auth', 'role:admin'])->prefix('usuario')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('admin.usuarios');
    Route::get('listar', [UserController::class, 'index'])->name('admin.usuarios.listar');
    Route::get('editar/{id}', [UserController::class, 'edit'])->name('admin.usuarios.edit');
    Route::get('delete/{id}', [UserController::class, 'delete'])->name('admin.usuarios.delete');
    Route::get('cadastrar', [UserController::class, 'index'])->name('admin.usuarios.create');
    Route::post('cadastrar', [UserController::class, 'create'])->name('admin.usuarios.create.do');
    Route::post('atualizar', [UserController::class, 'update'])->name('admin.usuarios.update');
    Route::get('search-pessoa', [UserController::class, 'searchPessoa'])->name('admin.usuarios.search-pessoa');


    /*Rotas funções*/
    Route::get('funcao', [RoleController::class, 'index'])->name('admin.usuarios.role');
    Route::get('funcao/editar/{id}', [RoleController::class, 'edit'])->name('admin.usuarios.role.edit');
    Route::post('funcao/editar', [RoleController::class, 'update'])->name('admin.usuarios.role.edit.do');
    Route::get('funcao/novo', [RoleController::class, 'create'])->name('admin.usuarios.role.create');
    Route::post('funcao/novo', [RoleController::class, 'save'])->name('admin.usuarios.role.create.do');
    Route::get('funcao/delete/{id}', [RoleController::class, 'delete'])->name('admin.usuarios.role.delete');

    /*Rotas permission*/
    Route::get('permissao', [PermissionController::class, 'index'])->name('admin.usuarios.permission');
    Route::get('permissao/editar/{id}', [PermissionController::class, 'edit'])->name('admin.usuarios.permission.edit');
    Route::post('permissao/editar', [PermissionController::class, 'update'])->name('admin.usuarios.permission.edit.do');
    Route::get('permissao/novo', [PermissionController::class, 'create'])->name('admin.usuarios.permission.create');
    Route::post('permissao/novo', [PermissionController::class, 'save'])->name('admin.usuarios.permission.create.do');
    Route::get('permissao/delete/{id}', [PermissionController::class, 'delete'])->name('admin.usuarios.permission.delete');
});
/*Rotas sem autenticação*/
Route::prefix('producao')->group(function () {
    /*Rotas de acompanhamento de ordem */
    Route::get('acompanha-ordem/h^f7dbtz^E1tj8xAZ6aEy7gsQ4ReEBYdo', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem');
    Route::get('acompanha-ordem/h^f7dbtz^E1tj8xAZ6aEy7gsQ4ReEBYdo{codigo_barras}', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem.barras');
    Route::post('acompanha-ordem/h^f7dbtz^E1tj8xAZ6aEy7gsQ4ReEBYdo', [AcompanhaOrdemController::class, 'statusOrdem'])->name('admin.producao.acompanha.ordem.do');

    Route::get('search-operador', [ExecutorController::class, 'searchExecutor'])->name('search-operador');
    Route::get('meta-operador', [MetaOperadorController::class, 'index'])->name('meta-operador');
    

    /*Produtividade Executores*/
    Route::get('produtividade-executores/quadrante-1', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante1');
    Route::get('produtividade-executores/quadrante-2', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante2');
    Route::get('produtividade-executores/quadrante-3', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante3');
    Route::get('produtividade-executores/quadrante-4', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante4');
});
Route::middleware(['auth', 'role:admin|portaria'])->group(function () {
    Route::prefix('portaria')->group(function () {
        Route::get('movimento/entrada', [PortariaController::class, 'index'])->name('admin.portaria.entrada');
        Route::get('movimento/saida', [PortariaController::class, 'index'])->name('admin.portaria.saida');

        Route::post('movimento/entrada', [PortariaController::class, 'list'])->name('portaria.entrada');
        Route::post('movimento/saida', [PortariaController::class, 'list'])->name('portaria.saida');

        Route::post('movimento/entrada/do', [PortariaController::class, 'saveEntrada'])->name('portaria.entrada.do');
        Route::post('movimento/saida/do', [PortariaController::class, 'saveSaida'])->name('portaria.saida.do');

        Route::get('relatorio/movimento', [PortariaController::class, 'movimentos'])->name('portaria.movimentos');
        Route::get('get-movimento', [PortariaController::class, 'getMovimento'])->name('get.portaria.movimentos');
    });
    /*Rotas cadastro motorista veiculos */
    Route::prefix('veiculo')->group(function () {
        Route::get('listar', [VeiculoController::class, 'index'])->name('listar.motorista.veiculos');
        Route::get('get-motorista-veiculos', [VeiculoController::class, 'getMotoristaVeiculos'])->name('buscar.motorista.veiculo');
        Route::post('store', [VeiculoController::class, 'store'])->name('motorista-veiculo.store');
        Route::get('edit/{id}', [VeiculoController::class, 'edit'])->name('motorista-veiculo.edit');
        Route::post('edit/{id}/do', [VeiculoController::class, 'update'])->name('motorista-veiculo.update');
        Route::delete('delete/{id}', [VeiculoController::class, 'destroy'])->name('motorista-veiculo.destroy');
    });
    /*Rotas Marca Veiculo */
    Route::prefix('marca/veiculo')->group(function () {
        Route::get('listar', [MarcaVeiculoController::class, 'create'])->name('marca-veiculo');
        Route::post('cadastrar/do', [MarcaVeiculoController::class, 'save'])->name('marca-veiculo.salvar');
        Route::post('delete', [MarcaVeiculoController::class, 'delete'])->name('marca-veiculo.delete');
        Route::post('editar', [MarcaVeiculoController::class, 'update'])->name('marca-veiculo.update');
    });
    /*Rotas Modelos Veiculos */
    Route::prefix('modelo/veiculo')->group(function () {
        Route::get('listar', [ModeloVeiculoController::class, 'create'])->name('modelo-veiculo');
        Route::post('cadastrar/do', [ModeloVeiculoController::class, 'save'])->name('modelo-veiculo.salvar');
        Route::post('delete', [ModeloVeiculoController::class, 'delete'])->name('modelo-veiculo.delete');
        Route::post('editar', [ModeloVeiculoController::class, 'update'])->name('modelo-veiculo.update');
    });
    /*Rotas Marca-Modelos Veiculos */
    Route::prefix('marca-modelo-frota/veiculo')->group(function () {
        Route::get('listar', [MarcaModeloFrotaController::class, 'index'])->name('marca-modelo.index');
        Route::get('get-marca-modelos', [MarcaModeloFrotaController::class, 'getMarcaModelos'])->name('get-marca-modelos');
        Route::post('store', [MarcaModeloFrotaController::class, 'store'])->name('marca-modelo.store');
        Route::get('edit/{id}', [MarcaModeloFrotaController::class, 'edit'])->name('marca-modelo.update');
        Route::post('edit/{id}/do', [MarcaModeloFrotaController::class, 'update'])->name('marca-modelo.update.do');
        Route::delete('delete/{id}', [MarcaModeloFrotaController::class, 'destroy'])->name('marca-modelo.delete');
    });
    /*Rotas para pesquisar pela placa */
    Route::prefix('placa')->group(function () {
        Route::post('autocomplete', [PortariaController::class, 'autocomplete'])->name('placa.autocomplete');
        Route::get('search', [PortariaController::class, 'search'])->name('placa.search');
    });
});
Route::middleware(['auth'])->group(function () {
    Route::prefix('comercial')->group(function () {
        Route::middleware(['auth', 'permission:ver-comercial-norte'])->group(function () {
            Route::get('ivorecap-norte', [ComercialController::class, 'ivoComercialNorte'])->name('comercial.ivo-norte');
        });
        Route::middleware(['auth', 'permission:ver-comercial-sul'])->group(function () {
            Route::get('ivorecap-sul', [ComercialController::class, 'ivoComercialSul'])->name('comercial.ivo-sul');
            Route::middleware(['permission:ver-cancela-nota'])->group(function () {
                /* Cancelar nota - usuario */
                Route::get('movimento/cancelar-nota', [CancelarNotaController::class, 'cancelarNota'])->name('comercial.cancela-nota');
                Route::post('cancelar-nota-do', [CancelarNotaController::class, 'getCancelarNota'])->name('comercial.cancela-nota-do');
                Route::get('movimento/search-nota', [CancelarNotaController::class, 'SearchNota'])->name('comercial.search-nota');
                Route::get('envio-email', [CancelarNotaController::class, 'envioEmail'])->name('comercial.envio-email');
            });
        });
        Route::middleware(['permission:ver-rel-cobranca-sul'])->group(function () {
            Route::get('movimento/rel-cobranca', [RelatorioCobrancaController::class, 'index'])->name('comercial.rel-cobranca-sul');
            Route::get('movimento/get-lista-cobranca', [RelatorioCobrancaController::class, 'getListCobranca'])->name('get-list-cobranca');
            Route::get('movimento/get-cobranca-filtro', [RelatorioCobrancaController::class, 'getListCobrancaFiltro'])->name('get-cobranca-filtro');
            Route::get('movimento/get-cobranca-cnpj', [RelatorioCobrancaController::class, 'getListCobrancaFiltroCnpj'])->name('get-cobranca-filtro-cnpj');
        });
        Route::middleware(['permission:ver-pedidos-coletados-acompanhamento'])->group(function () {
            // Bloqueio de Pedidos
            Route::get('movimento/acompanha-pedidos', [BloqueioPedidosController::class, 'index'])->name('bloqueio-pedidos');
            Route::get('movimento/get-bloqueio-pedidos', [BloqueioPedidosController::class, 'getBloqueioPedido'])->name('get-bloqueio-pedidos');
            Route::get('movimento/get-pedidos', [BloqueioPedidosController::class, 'getPedidoAcompanhar'])->name('get-pedido-acompanhar');
            Route::get('movimento/get-item-pedidos/{id}', [BloqueioPedidosController::class, 'getItemPedidoAcompanhar'])->name('get-item-pedido-acompanhar');
            Route::get('movimento/get-detalhe-item-pedidos/{id}', [BloqueioPedidosController::class, 'getDetalheItemPedidoAcompanhar'])->name('get-detalhe-item-pedido');
        });

        Route::middleware(['auth', 'role:admin|controladoria'])->group(function () {
            /* Lista de notas a cancelar - Role Controladoria*/
            Route::get('movimento/lista-notas-cancelar', [CancelarNotaController::class, 'listAll'])->name('comercial.list-nota-all');
            Route::get('get-lista-notas-cancelar', [CancelarNotaController::class, 'getListAll'])->name('comercial.get-list-nota-all');


            Route::get('cadastro/regiao-comercial', [RegiaoComercialController::class, 'index'])->name('regiao-comercial.index');
            Route::get('get-regiao-comercial', [RegiaoComercialController::class, 'create'])->name('get-regiao-comercial.create');
            Route::get('get-table-regiao-usuario', [RegiaoComercialController::class, 'list'])->name('get-table-regiao-usuario');
            Route::post('edit-regiao-usuario', [RegiaoComercialController::class, 'update'])->name('edit-regiao-usuario');
            Route::delete('regiao-usuario-delete', [RegiaoComercialController::class, 'destroy'])->name('regiao-usuario.delete');

            Route::get('cadastro/area-comercial', [AreaComercialController::class, 'index'])->name('area-comercial.index');
            Route::get('get-area-comercial', [AreaComercialController::class, 'create'])->name('get-area-comercial.create');
            Route::get('get-table-area-usuario', [AreaComercialController::class, 'list'])->name('get-table-area-usuario');
            Route::post('edit-area-usuario', [AreaComercialController::class, 'update'])->name('edit-area-usuario');
            Route::delete('area-usuario-delete', [AreaComercialController::class, 'destroy'])->name('area-usuario.delete');
        });
    });
});
Route::middleware(['auth'])->group(function () {
    Route::prefix('diretoria')->group(function () {
        Route::middleware(['auth', 'permission:ver-diretoria-norte'])->group(function () {
            Route::get('ivorecap-norte', [ComercialController::class, 'ivoDiretoriaNorte'])->name('diretoria.ivo-norte');
        });
        Route::middleware(['auth', 'permission:ver-diretoria-sul'])->group(function () {
            Route::get('ivorecap-sul', [ComercialController::class, 'ivoDiretoriaSul'])->name('diretoria.ivo-sul');
        });
    });
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    //Rotas Pessoa
    Route::prefix('pessoa')->group(function () {
        Route::get('listar', [PessoaController::class, 'index'])->name('pessoa.index');
        Route::get('get-pessoa', [PessoaController::class, 'getpessoa'])->name('get-pessoa');
        Route::post('store', [PessoaController::class, 'store'])->name('pessoa.store');
        Route::get('edit/{id}', [PessoaController::class, 'edit'])->name('pessoa.update');
        Route::post('edit/{id}/do', [PessoaController::class, 'update'])->name('pessoa.update.do');
        Route::delete('delete/{id}', [PessoaController::class, 'destroy'])->name('pessoa.delete');
    });
    //Rotas Email
    Route::prefix('email')->group(function () {
        Route::get('listar', [EmailController::class, 'index'])->name('email.index');
        Route::get('get-email', [EmailController::class, 'getemail'])->name('get-email');
        Route::post('store', [EmailController::class, 'store'])->name('email.store');
        Route::get('edit/{id}', [EmailController::class, 'edit'])->name('email.update');
        Route::post('edit/{id}/do', [EmailController::class, 'update'])->name('email.update.do');
        Route::delete('delete/{id}', [EmailController::class, 'destroy'])->name('email.delete');
    });
    Route::prefix('powerbi')->group(function () {
        Route::get('index', [PowerBiEmbeddedController::class, 'index'])->name('powerbi.index');
    });
    Route::prefix('inadimplencia')->group(function () {
        Route::get('index', [InadimplenciaController::class, 'index'])->name('inadimplencia.index');
        Route::get('get-table-vencer', [InadimplenciaController::class, 'getVencer'])->name('inadimplencia.get-vencer');
        Route::get('get-table-details-area-vencer/{id}', [InadimplenciaController::class, 'getDetailsArea'])->name('get-details-vencer');
        Route::get('get-table-details-regiao-vencer/{id}', [InadimplenciaController::class, 'getDetailsRegiao'])->name('get-details-area-vencer');
    });
});
Route::middleware(['auth', 'role:admin|cobranca'])->group(function () {
    Route::prefix('cobranca')->group(function () {
        Route::get('index', [CobrancaController::class, 'index'])->name('cobranca.index');
        Route::get('detalhe-agenda/usuario/{cdusuario}/data/{dt}', [CobrancaController::class, 'DetalheAgenda'])->name('cobranca.detalhe-agenda');
        Route::get('clientes-novos/usuario/{cdusuario}/data/{dt}', [CobrancaController::class, 'ClientesNovos'])->name('cobranca.clientes-novos');
        Route::get('agenda/data', [CobrancaController::class, 'AgendaData'])->name('cobranca.agenda.mes');
        Route::get('clientes-novos/data', [CobrancaController::class, 'ClientesNovosMes'])->name('cobranca.clientes-novos-mes');
        Route::get('envio-follow-up', [CobrancaController::class, 'searchEnvio'])->name('search-envio');
        Route::get('get-search-follow', [CobrancaController::class, 'getSearchEnvio'])->name('get-search-envio');
        Route::get('get-email-follow/{id}', [CobrancaController::class, 'getEmailEnvio'])->name('get-email-follow');
        Route::get('get-envio-iagente', [CobrancaController::class, 'getSubmitIagente'])->name('get-envio-iagente');
        Route::delete('delete-email-webhook-iagente', [CobrancaController::class, 'DeleteEmailWebhookIagente'])->name('delete-email-webhook-iagente');
        

        Route::get('chart-data', [CobrancaController::class, 'chartLineAjax'])->name('cobranca.chart-api');
        Route::get('get-qtd-clients-novos', [CobrancaController::class, 'qtdClientesNovosMes'])->name('get-qtd-clients-novos');
        Route::get('get-fp-clients-novos', [CobrancaController::class, 'listClientFormPgto'])->name('get-fp-clients-novos');
    });
});
Route::middleware(['auth', 'role:admin|producao'])->group(function () {
    Route::prefix('importa-item-junsoft')->group(function () {
        Route::get('index', [ImportaItemJunsoftController::class, 'index'])->name('importa.index');
        Route::post('id-marca-ajax', [ImportaItemJunsoftController::class, 'AjaxImportaItem'])->name('importa-item.index');
    });
    Route::prefix('estoque-entrada')->group(function () {
        Route::get('index', [LoteEntradaEstoqueController::class, 'index'])->name('estoque.index');
        Route::post('cria-lote', [LoteEntradaEstoqueController::class, 'store'])->name('estoque.cria-lote');
        Route::get('get-lotes', [LoteEntradaEstoqueController::class, 'getLotes'])->name('estoque.get-lotes');
        Route::post('finaliza-lote', [LoteEntradaEstoqueController::class, 'finishLote'])->name('estoque.finish-lote');
        Route::delete('delete-lote', [LoteEntradaEstoqueController::class, 'delete'])->name('estoque.delete-lote');

        Route::get('add-item-lote/{id}', [ItemLoteEntradaEstoqueController::class, 'index'])->name('add-item-lote.index');
        Route::get('get-busca-item/{cd_barras}', [ItemLoteEntradaEstoqueController::class, 'getBuscaItem'])->name('get-item-lote');
        Route::get('get-busca-item',  function () {
            return;
        });
        Route::post('add-item-lote/store', [ItemLoteEntradaEstoqueController::class, 'store'])->name('add-item-lote.store');
        Route::delete('delete-item-lote', [ItemLoteEntradaEstoqueController::class, 'delete'])->name('delete-item-lote');

        Route::get('item-lote-fechado/{id}', [ItemLoteEntradaEstoqueController::class, 'listItemLote'])->name('item-lote-fechado');
    });
    Route::prefix('producao')->group(function () {
        /* Routas Etapas */
        Route::get('etapas', [ProducaoEtapaController::class, 'index'])->name('admin.producao.etapas');
        Route::post('etapas', [ProducaoEtapaController::class, 'index'])->name('admin.producao.etapas.do');
        Route::get('troca-servico', [ProducaoEtapaController::class, 'trocaServico'])->name('producao.troca-servico');
        Route::get('get-troca-servico', [ProducaoEtapaController::class, 'getChangeService'])->name('producao.get-troca-servico');
        Route::get('get-grid-ordem-troca-servico', [ProducaoEtapaController::class, 'getChangeServiceOrdem'])->name('producao.get-troca-servico-ordem');
        
        /*Rotas Quantide lote e atrasos*/
        Route::get('lote-pcp', [LotePcpController::class, 'index'])->name('admin.lote.pcp');
        Route::get('lote-pcp/{nr_lote}/pneus-lote', [PneusLotePcpController::class, 'index'])->name('admin.lote.pneu.pcp');

        /* Rotas para informações QGC Bridgestone */
        Route::prefix('gqc-bridgestone')->group(function () {
            Route::get('pneus-marcas', [GqcBridgestoneController::class, 'pneusFaturadosMarcas'])->name('gqc-pneus-faturados-marca');
            Route::get('get-pneus-marcas', [GqcBridgestoneController::class, 'getPneusFaturadosMarcas'])->name('get-pneus-faturados-marca');
            Route::get('get-buscar-pneus-marcas', [GqcBridgestoneController::class, 'getBuscarPneusMarcas'])->name('get-gqc-buscar-pneus-marca');
        });

        /* Rotas para informações QGC Bridgestone */
        Route::prefix('api-new-age')->group(function () {
            Route::get('index', [ApiNewAgeController::class, 'index'])->name('api-new-age.index');
            Route::get('get-pneus-bandag', [ApiNewAgeController::class, 'GetPneusEnviarBandag'])->name('get-pneus-bandag');
            Route::get('callXmlProcess', [ApiNewAgeController::class, 'callXmlProcess'])->name('NewAgecallXmlProcess');
            Route::get('comando', [ApiNewAgeController::class, 'executeComando'])->name('api-new-age-comando');
            Route::get('importar-pneus-junsoft', [ApiNewAgeController::class, 'ImportPneus'])->name('api-new-age-import-pneus');
            Route::get('editar-ordem', [ApiNewAgeController::class, 'EditOrdens'])->name('api-new-age-edit-pneus');
            Route::get('ordem-divergencia', [ApiNewAgeController::class, 'Divergencia'])->name('api-new-age-divergencia-pneus');
        });
    });
});
