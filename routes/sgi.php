<?php

use App\Http\Controllers\Admin\Procedimento\ProcedimentoAprovadorController;
use App\Http\Controllers\Admin\Sgi\SgiController;
use App\Models\ProcedimentoAprovador;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:sgi|admin'])->group(function () {
    Route::prefix('sgi')->group(function () {
        Route::get('index', [SgiController::class, 'index'])->name('sgi.index');
        Route::post('store', [SgiController::class, 'store'])->name('sgi.store');
        Route::get('edit', [SgiController::class, 'edit'])->name('sgi.edit');
        Route::post('update', [SgiController::class, 'update'])->name('sgi.update');
        Route::get('list-sgi', [SgiController::class, 'GetProcedimento'])->name('sgi.get-procedimento');
        Route::get('arquivo', [SgiController::class, 'showPDF'])->name('sgi.show-pdf');
        // Route::get('send-email', [SgiController::class, 'envioEmail'])->name('sgi.send-email');
        Route::delete('delete', [SgiController::class, 'destroy'])->name('sgi.delete');
        Route::get('store-publish', [SgiController::class, 'storePublish'])->name('sgi.store.publish');
        Route::post('delete-publish', [SgiController::class, 'destroyPublish'])->name('sgi.delete-publish');
        // Route::get('procedimento-pendentes-aprovadores', [SgiController::class, 'approverOutstanding'])->name('sgi.outstanding');        
        // Route::post('store-update', [SgiController::class, 'storeUpdateFileEdit'])->name('sgi.store-update');

    });
});
Route::middleware(['auth', 'permission:ver-sgi'])->group(function () {
    Route::prefix('sgi-publicos')->group(function () {
        Route::get('liberados', [SgiController::class, 'sgiPublish'])->name('sgi.publish');
        Route::get('get-liberados', [SgiController::class, 'GetSgisPublish'])->name('get-sgis.publish');
    });
});
