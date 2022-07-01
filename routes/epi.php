<?php

use App\Http\Controllers\Admin\Producao\EpisController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:ver-controle-epi'])->group(function () {
    Route::prefix('producao')->group(function () {
        Route::get('controle-epis', [EpisController::class, 'index'])->name('epis.index');
        Route::get('getSearchSetor', [EpisController::class, 'searchSetores'])->name('search-etapas-producao');
        Route::get('epi-setor-operador', [EpisController::class, 'SaveEpiSetorOperador'])->name('save-epi-etapas-operador');
        Route::get('uso-epi-setor-operador', [EpisController::class, 'RelatorioUsoEpi'])->name('get-uso-epis');
        Route::get('get-buscar-executor', [EpisController::class, 'SearchExecutor'])->name('get-buscar-executor');
    });
});
