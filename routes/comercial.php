<?php

use App\Exports\PedidoPneuLaudoAssociadoRecusadoExport;
use App\Http\Controllers\Admin\Comercial\ComercialController;
use App\Http\Controllers\Admin\Comercial\ComissaoVendedorController;
use App\Http\Controllers\Admin\Comercial\LiberaOrdemComissaoController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::middleware(['auth', 'permission:ver-libera-ordem'])->group(function () {
    Route::prefix('libera-ordem-comercial')->group(function () {
        Route::get('index', [LiberaOrdemComissaoController::class, 'index'])->name('libera-ordem-comissao.index');

        Route::get('get-ordem-bloqueadas-comercial', [LiberaOrdemComissaoController::class, 'getListOrdemBloqueadas'])->name('get-ordens-bloqueadas-comercial');
        Route::get('get-pneus-ordem-bloqueadas-comercial/{id}', [LiberaOrdemComissaoController::class, 'getListPneusOrdemBloqueadas'])->name('get-pneus-ordens-bloqueadas-comercial');
        Route::post('save-libera-pedido', [LiberaOrdemComissaoController::class, 'saveLiberaPedido'])->name('save-libera-pedido');
    });
});

Route::middleware(['auth', 'permission:ver-atualiza-pedido-associado'])->group(function () {
    Route::prefix('comissao')->group(function () {
        Route::get('comissao-vendedor', [ComissaoVendedorController::class, 'comissaoLiquidacao'])->name('comissao-liquidacao.create');
        Route::get('list-comissao-vendedor', [ComissaoVendedorController::class, 'listComissaoLiquidacao'])->name('comissao-liquidacao.list');
    });

    Route::prefix('pedido-laudo-associado-recusado')->group(function () {
        Route::get('update-status', [ComercialController::class, 'updateStatusPedidoPneuLaudo'])->name('pedido-laudo-associado.update');

       
    });
});
