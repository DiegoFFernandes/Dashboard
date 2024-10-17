<?php

use App\Http\Controllers\Admin\Compras\SolicitacaoCompraController;
use App\Http\Controllers\Admin\Financeiro\FinanceiroController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|compras'])->group(function () {
    Route::prefix('compras')->group(function () {
        Route::get('libera-pedido-compra', [SolicitacaoCompraController::class, 'liberaPedidoCompras'])->name('libera-pedido-compra.index');
        Route::get('get-list-pedido-compra-bloqueadas', [SolicitacaoCompraController::class, 'listPedidosCompraBloqueadas'])->name('pedidos-compra-bloqueadas.list');
        // Route::post('get-list-contas-bloqueadas-historico', [FinanceiroController::class, 'listHistoricoContasBloqueadas'])->name('historico-contas-bloqueadas.list');
        Route::get('get-list-pedido-compra-bloqueadas-item', [SolicitacaoCompraController::class, 'listPedidosCompraBlqueadasItem'])->name('pedidos-compra-bloqueadas-item.list');
       
        
        
        Route::post('update-status-compra-bloqueadas', [SolicitacaoCompraController::class, 'updateStatusComprasBloqueadas'])->name('compras-bloqueadas.update');
        
    });
});

