<?php

use App\Http\Controllers\Admin\Comercial\LiberaOrdemComissaoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|coordenador|gerencia'])->group(function () {
    Route::prefix('libera-ordem-comercial')->group(function () {
        Route::get('index', [LiberaOrdemComissaoController::class, 'index'])->name('libera-ordem-comissao.index');
        
        Route::get('get-ordem-bloqueadas-comercial', [LiberaOrdemComissaoController::class, 'getListOrdemBloqueadas'])->name('get-ordens-bloqueadas-comercial');
        Route::get('get-pneus-ordem-bloqueadas-comercial/{id}', [LiberaOrdemComissaoController::class, 'getListPneusOrdemBloqueadas'])->name('get-pneus-ordens-bloqueadas-comercial');
        Route::get('save-libera-pedido', [LiberaOrdemComissaoController::class, 'saveLiberaPedido'])->name('save-libera-pedido');
    
    });
});
