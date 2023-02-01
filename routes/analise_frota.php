<?php

use App\Http\Controllers\Admin\AnaliseFrota\AnaliseFrotaController;
use App\Http\Controllers\Admin\AnaliseFrota\ItemAnaliseFrotaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('analise-frota')->group(function () {
        Route::get('index', [AnaliseFrotaController::class, 'index'])->name('analise-frota.index');
        
        Route::get('create', [AnaliseFrotaController::class, 'create'])->name('analise-frota.create');
        Route::get('get-list-data', [AnaliseFrotaController::class, 'getListaData'])->name('list-analise-create');        

        Route::post('delete', [AnaliseFrotaController::class, 'delete'])->name('delete-analysis');


        Route::get('item-analise/{id}', [ItemAnaliseFrotaController::class, 'index'])->name('item-analysis');

        Route::post('add-item-analise', [ItemAnaliseFrotaController::class, 'store'])->name('store-item-analysis');
        Route::get('get-item-analise', [ItemAnaliseFrotaController::class, 'getItemAnalise'])->name('get-item-analysis');

        Route::post('delete-item-analise', [ItemAnaliseFrotaController::class, 'destroyItem'])->name('delete-item-analysis');
    });
});
