<?php

use App\Http\Controllers\Admin\AcompanhaOrdemController;
use App\Http\Controllers\Admin\LotePcpController;
use App\Http\Controllers\Admin\MarcaVeiculoController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PneusLotePcpController;
use App\Http\Controllers\Admin\PortariaController;
use App\Http\Controllers\Admin\ProducaoEtapaController;
use App\Http\Controllers\admin\ProdutividadeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VeiculoController;
use App\Http\Controllers\Auth\LoginController;

use App\Models\Veiculo;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear-cache-all', function () {

 Artisan::call('cache:clear');

 dd("Cache Clear All");

});

/* Rotas de Login */
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login/do', [LoginController::class, 'Login'])->name('admin.login.do');
Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('/admin', [LoginController::class, 'dashboard'])->name('admin.dashborad');

Route::middleware(['auth', 'role:admin|producao'])->group(function () {
 Route::prefix('producao')->group(function () {
  /* Routas Etapas */
  Route::get('etapas', [ProducaoEtapaController::class, 'index'])->name('admin.producao.etapas');
  Route::post('etapas', [ProducaoEtapaController::class, 'index'])->name('admin.producao.etapas.do');

  /*Rotas Quantide lote e atrasos*/
  Route::get('lote-pcp', [LotePcpController::class, 'index'])->name('admin.lote.pcp');
  Route::get('lote-pcp/{nr_lote}/pneus-lote', [PneusLotePcpController::class, 'index'])->name('admin.lote.pneu.pcp');
 });

 Route::middleware(['auth', 'role:admin'])->prefix('usuarios')->group(function () {
  Route::get('/', [UserController::class, 'index'])->name('admin.usuarios');
  Route::get('listar', [UserController::class, 'index'])->name('admin.usuarios.listar');
  Route::get('editar/{id}', [UserController::class, 'edit'])->name('admin.usuarios.edit');
  Route::get('delete/{id}', [UserController::class, 'delete'])->name('admin.usuarios.delete');
  Route::get('cadastrar', [UserController::class, 'index'])->name('admin.usuarios.create');
  Route::post('cadastrar', [UserController::class, 'create'])->name('admin.usuarios.create.do');
  Route::post('atualizar', [UserController::class, 'update'])->name('admin.usuarios.update');

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
  Route::post('permissão/novo', [PermissionController::class, 'save'])->name('admin.usuarios.permission.create.do');
  Route::get('permissao/delete/{id}', [PermissionController::class, 'delete'])->name('admin.usuarios.permission.delete');
 });

});

Route::prefix('producao')->group(function () {
 /*Rotas de acompanhamento de ordem */
 Route::get('acompanha-ordem', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem');
 Route::get('acompanha-ordem/{codigo_barras}', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem.barras');
 Route::post('acompanha-ordem', [AcompanhaOrdemController::class, 'statusOrdem'])->name('admin.producao.acompanha.ordem.do');

 /*Produtividade Executores*/
 Route::get('produtividade-executores/quadrante-1', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante1');
 Route::get('produtividade-executores/quadrante-2', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante2');
 Route::get('produtividade-executores/quadrante-3', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante3');
 Route::get('produtividade-executores/quadrante-4', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante4');
});

Route::middleware(['auth', 'role:admin|portaria'])->group(function () {
 Route::prefix('portaria')->group(function () {
  Route::get('cadastrar/entrada', [PortariaController::class, 'index'])->name('admin.portaria.entrada');
  Route::post('cadastrar/entrada/do', [PortariaController::class, 'create'])->name('admin.portaria.entrada.do');
  Route::get('cadastrar/saida', [PortariaController::class, 'index'])->name('admin.portaria.saida');
 });
 Route::prefix('veiculo')->group(function () {
  Route::get('cadastrar', [VeiculoController::class, 'create'])->name('admin.cadastrar.motorista.veiculos');
  Route::post('cadastrar/do', [VeiculoController::class, 'save'])->name('admin.cadastrar-do.motorista.veiculos');
  Route::get('listar', [VeiculoController::class, 'list'])->name('admin.listar.motorista.veiculos');
  Route::get('editar/{id}', [VeiculoController::class, 'edit'])->name('admin.editar.motorista.veiculos');  
  Route::post('editar/do', [VeiculoController::class, 'update'])->name('admin.editar-do.motorista.veiculos');
  Route::get('load_marcas', [VeiculoController::class, 'loadMarcas'])->name('load_marcas');
  Route::get('load_modelos', [VeiculoController::class, 'loadModelos'])->name('load_modelos');
 });
 Route::prefix('marca/veiculo')->group(function(){
     Route::get('cadastrar', [MarcaVeiculoController::class, 'create'])->name('marca-veiculo.cadastrar');
     Route::post('cadastrar/do', [MarcaVeiculoController::class, 'save'])->name('marca-veiculo.salvar');
 });

});

