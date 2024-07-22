<?php

use App\Http\Controllers\Admin\Contabilidade\ParametroContabilController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:ver-gerenciador-contabil'])->group(function () {
    Route::prefix('junsoft')->group(function () {
        Route::get('gerenciador-contabilidade', [ParametroContabilController::class, 'index'])->name('parm-contabilidade.index');
        Route::get('date-contabilidade', [ParametroContabilController::class, 'storeDate'])->name('parm-contabilidade.store-date');
        Route::get('status-gerenciador', [ParametroContabilController::class, 'status'])->name('status-gerenciador-contabil');        
    });
});