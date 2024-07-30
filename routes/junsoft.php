<?php

use App\Http\Controllers\Admin\Contabilidade\ParametroContabilController;
use App\Http\Controllers\Admin\Junsoft\ItemCentroResultadoController;
use App\Http\Controllers\Admin\Junsoft\SubgrupoCentroResultadoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:ver-gerenciador-contabil'])->group(function () {
    Route::prefix('junsoft')->group(function () {
        Route::get('gerenciador-contabilidade', [ParametroContabilController::class, 'index'])->name('parm-contabilidade.index');
        Route::get('date-contabilidade', [ParametroContabilController::class, 'storeDate'])->name('parm-contabilidade.store-date');
        Route::get('status-gerenciador', [ParametroContabilController::class, 'status'])->name('status-gerenciador-contabil');        
    });
});

Route::middleware(['auth', 'role:admin|controladoria'])->group(function () {
    Route::prefix('junsoft')->group(function () {
        Route::get('item-centro-resultado', [ItemCentroResultadoController::class, 'index'])->name('item-centro-resultado.index');
        Route::get('ajax-item-centro-resultado', [ItemCentroResultadoController::class, 'listItemCentroResultado'])->name('ajax-item-centro-resultado.list');
        Route::post('ajax-edit-item-centro-resultado', [ItemCentroResultadoController::class, 'EditItemCentroResultado'])->name('ajax-item-centro-resultado.edit');
        Route::post('ajax-store-sub-centro-resultado', [SubgrupoCentroResultadoController::class, 'StoreSubCentroResultado'])->name('ajax-sub-centro-resultado.store');
        Route::post('ajax-delete-sub-centro-resultado', [SubgrupoCentroResultadoController::class, 'DeleteSubCentroResultado'])->name('ajax-sub-centro-resultado.delete');
        Route::get('ajax-list-sub-centro-resultado', [SubgrupoCentroResultadoController::class, 'listSubgrupoCentroResultado'])->name('ajax-sub-centro-resultado.list');
        
    });
});