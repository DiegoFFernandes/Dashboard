<?php

use App\Http\Controllers\Admin\AnaliseFrota\AnaliseFrotaController;
use App\Http\Controllers\Admin\AnaliseFrota\ItemAnaliseFrotaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:ver-analise-frota'])->group(function () {
    Route::prefix('analise-frota')->group(function () {
        Route::get('index', [AnaliseFrotaController::class, 'index'])->name('analise-frota.index');

        Route::get('create', [AnaliseFrotaController::class, 'create'])->name('analise-frota.create');
        Route::get('edit', [AnaliseFrotaController::class, 'update'])->name('analise-frota.update');
        
        Route::get('get-list-data', [AnaliseFrotaController::class, 'getListaData'])->name('list-analise-create');
        Route::post('delete', [AnaliseFrotaController::class, 'delete'])->name('delete-analysis');


        Route::get('item-analise/{id}', [ItemAnaliseFrotaController::class, 'index'])->name('item-analysis');

        Route::post('add-item-analise', [ItemAnaliseFrotaController::class, 'store'])->name('store-item-analysis');
        Route::post('edit-item-analise', [ItemAnaliseFrotaController::class, 'edit'])->name('edit-item-analysis');

        Route::get('get-item-analise', [ItemAnaliseFrotaController::class, 'getItemAnalise'])->name('get-item-analysis');
        Route::get('get-pictures-analise', [ItemAnaliseFrotaController::class, 'getPicturesItemAnalysis'])->name('get-picture-item-analysis');
        Route::post('delete-item-analise', [ItemAnaliseFrotaController::class, 'destroyItem'])->name('delete-item-analysis');
        
        Route::post('finish-analise', [AnaliseFrotaController::class, 'finishAnalysis'])->name('finish-analysis');
        
        Route::get('resumo-coleta-analise/{id}', [ItemAnaliseFrotaController::class, 'ResumeItemAnaliseAll'])->name('-resume-analysis');
       
        Route::get('imprimir-coleta-analise/{id}', [ItemAnaliseFrotaController::class, 'getPrintItemAnaliseAll'])->name('get-print-analysis');
       
    });
});
