<?php

use App\Http\Controllers\Admin\Financeiro\FinanceiroController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|financeiro'])->group(function () {
    Route::prefix('financeiro')->group(function () {
        Route::get('index', [FinanceiroController::class, 'index'])->name('financeiro.index');
        
        Route::get('get-conciliacao', [FinanceiroController::class, 'getConciliacao'])->name('get-conciliacao');

        Route::get('libera-contas', [FinanceiroController::class, 'liberaContas'])->name('libera-contas.index');
        Route::get('get-list-contas-bloqueadas', [FinanceiroController::class, 'listContasBloqueadas'])->name('contas-bloqueadas.list');
        Route::get('get-list-contas-bloqueadas-historico', [FinanceiroController::class, 'listHistoricoContasBloqueadas'])->name('historico-contas-bloqueadas.list');
    });
});
