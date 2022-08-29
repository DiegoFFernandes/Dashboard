<?php

use App\Http\Controllers\Admin\Procedimento\ProcedimentoAprovadorController;
use App\Http\Controllers\Admin\Procedimento\ProcedimentoController;
use App\Models\ProcedimentoAprovador;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:ver-procedimento'])->group(function () {
    Route::prefix('procedimento')->group(function () {
        Route::get('index', [ProcedimentoController::class, 'index'])->name('procedimento.index');
        Route::post('store', [ProcedimentoController::class, 'store'])->name('procedimento.store');
        Route::get('edit', [ProcedimentoController::class, 'edit'])->name('procedimento.edit');
        Route::post('update', [ProcedimentoController::class, 'update'])->name('procedimento.update');
        Route::get('list-procedimento', [ProcedimentoController::class, 'GetProcedimento'])->name('procedimento.get-procedimento');
        Route::get('procedimento', [ProcedimentoController::class, 'showPDF'])->name('procedimento.show-pdf');
        Route::get('send-email', [ProcedimentoController::class, 'envioEmail'])->name('procedimento.send-email');
        Route::delete('delete', [ProcedimentoController::class, 'destroy'])->name('procedimento.delete');
        Route::get('store-publish', [ProcedimentoController::class, 'storePublish'])->name('procedimento.store.publish');
        Route::delete('delete-publish', [ProcedimentoController::class, 'destroyPublish'])->name('procedimento.delete-publish');
        
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('procedimento', [ProcedimentoController::class, 'showPDF'])->name('procedimento.show-pdf');
    
    Route::prefix('procedimento-aprovador')->group(function () {
        Route::get('autorizador', [ProcedimentoAprovadorController::class, 'index'])->name('procedimento.autorizador');
        Route::get('list-procedimento-para-liberar', [ProcedimentoAprovadorController::class, 'GetProcedimentoAprovador'])->name('procedimento.get-procedimento-aprovador');
        Route::get('store', [ProcedimentoAprovadorController::class, 'store'])->name('procedimento.aprovador.store');
    });

    Route::prefix('procedimento-reprovados')->group(function () {
        Route::get('reprovados', [ProcedimentoAprovadorController::class, 'GetProcedimentoReprovado'])->name('procedimento.reprovados');
        Route::get('chat-reprovados', [ProcedimentoAprovadorController::class, 'chatProcedimentoReprovado'])->name('procedimento.chat');
        Route::post('reprovados-replica', [ProcedimentoAprovadorController::class, 'updateReplica'])->name('procedimento.replica');
    });

    Route::prefix('procedimento-publicos')->group(function () {
        Route::get('liberados', [ProcedimentoController::class, 'procedimentoPublish'])->name('procedimento.publish');
        Route::get('get-liberados', [ProcedimentoController::class, 'GetprocedimentoPublish'])->name('get-procedimento.publish');
    
    });
});
