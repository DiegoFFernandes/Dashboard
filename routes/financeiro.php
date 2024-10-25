<?php

use App\Http\Controllers\Admin\Financeiro\AdiantamentoDespesasViagemController;
use App\Http\Controllers\Admin\Financeiro\FinanceiroController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|financeiro'])->group(function () {
    Route::prefix('financeiro')->group(function () {
        Route::get('index', [FinanceiroController::class, 'index'])->name('financeiro.index');

        Route::get('get-conciliacao', [FinanceiroController::class, 'getConciliacao'])->name('get-conciliacao');

        Route::get('libera-contas', [FinanceiroController::class, 'liberaContas'])->name('libera-contas.index');
        Route::get('get-list-contas-bloqueadas', [FinanceiroController::class, 'listContasBloqueadas'])->name('contas-bloqueadas.list');
        Route::post('get-list-contas-bloqueadas-historico', [FinanceiroController::class, 'listHistoricoContasBloqueadas'])->name('historico-contas-bloqueadas.list');
        Route::post('get-list-contas-bloqueadas-centro-resultado', [FinanceiroController::class, 'listCentroCustoContasBloqueadas'])->name('centroresultado-contas-bloqueadas.list');

        Route::post('update-status-contas-bloqueadas', [FinanceiroController::class, 'updateStatusContasBloqueadas'])->name('contas-bloqueadas.update');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('adiantamento-despesas')->group(function () {
        Route::get('comprovantes', [AdiantamentoDespesasViagemController::class, 'index'])->name('adiantamento-despesas.index');
        Route::get('saldo-despesa', [AdiantamentoDespesasViagemController::class, 'SaldoDespesa'])->name('adiantamento-despesas.saldo');
        Route::get('get-list-comprovante', [AdiantamentoDespesasViagemController::class, 'listComprovantes'])->name('adiantamento-despesas.list');

        Route::post('delete', [AdiantamentoDespesasViagemController::class, 'delete'])->name('delete-despesa');
        Route::post('update-visto', [AdiantamentoDespesasViagemController::class, 'vistoComprovante'])->name('visto-despesa');
        Route::post('store-adiantamento-despesa-vl-consumido', [AdiantamentoDespesasViagemController::class, 'StoreVlConsumido'])->name('store-adiantamento-despesas.vl_consumido');
        Route::post('update-adiantamento-despesa-vl-consumido', [AdiantamentoDespesasViagemController::class, 'UpdateVlConsumido'])->name('update-adiantamento-despesas.vl_consumido');
        
        Route::post('despesas-pictures', [AdiantamentoDespesasViagemController::class, 'viewPictures'])->name('adiantamento-despesas.pictures');
    });
});
