<?php

use App\Http\Controllers\Admin\Procedimento\ProcedimentoAprovadorController;
use App\Http\Controllers\Admin\Sgi\SgiController;
use App\Models\ProcedimentoAprovador;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:ver-procedimento'])->group(function () {
    Route::prefix('sgi')->group(function () {
        Route::get('index', [SgiController::class, 'index'])->name('sgi.index');
        // Route::post('store', [SgiController::class, 'store'])->name('sgi.store');
        // Route::get('edit', [SgiController::class, 'edit'])->name('sgi.edit');
        // Route::post('update', [SgiController::class, 'update'])->name('sgi.update');
        // Route::get('list-procedimento', [SgiController::class, 'GetProcedimento'])->name('sgi.get-procedimento');
        // Route::get('procedimento', [SgiController::class, 'showPDF'])->name('sgi.show-pdf');
        // Route::get('send-email', [SgiController::class, 'envioEmail'])->name('sgi.send-email');
        // Route::delete('delete', [SgiController::class, 'destroy'])->name('sgi.delete');
        // Route::get('store-publish', [SgiController::class, 'storePublish'])->name('sgi.store.publish');
        // Route::delete('delete-publish', [SgiController::class, 'destroyPublish'])->name('sgi.delete-publish');
        // Route::get('procedimento-sem-aprovador', [SgiController::class, 'storeNoApprover'])->name('sgi.store.noapprover');
        // Route::get('procedimento-pendentes-aprovadores', [SgiController::class, 'approverOutstanding'])->name('sgi.outstanding');        
        // Route::post('store-update', [SgiController::class, 'storeUpdateFileEdit'])->name('sgi.store-update');
        
    });
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('procedimento', [SgiController::class, 'showPDF'])->name('procedimento.show-pdf');

//     Route::prefix('procedimento-aprovador')->group(function () {
//         Route::get('autorizador', [ProcedimentoAprovadorController::class, 'index'])->name('procedimento.autorizador');
//         Route::get('list-procedimento-para-liberar', [ProcedimentoAprovadorController::class, 'GetProcedimentoAprovador'])->name('procedimento.get-procedimento-aprovador');
//         Route::get('store', [ProcedimentoAprovadorController::class, 'store'])->name('procedimento.aprovador.store');
//     });

//     Route::prefix('procedimento-reprovados')->group(function () {
//         Route::get('reprovados', [ProcedimentoAprovadorController::class, 'GetProcedimentoReprovado'])->name('procedimento.reprovados');
//         Route::get('chat-reprovados', [ProcedimentoAprovadorController::class, 'chatProcedimentoReprovado'])->name('procedimento.chat');
//         Route::post('reprovados-replica', [ProcedimentoAprovadorController::class, 'updateReplica'])->name('procedimento.replica');
//     });

//     Route::prefix('procedimento-publicos')->group(function () {
//         Route::get('liberados', [SgiController::class, 'procedimentoPublish'])->name('procedimento.publish');
//         Route::get('get-liberados', [SgiController::class, 'GetprocedimentoPublish'])->name('get-procedimento.publish');
//         Route::get('revisao-procedimento', [SgiController::class, 'reviseProcedimento'])->name('revision-procedimento.publish');
    
//     });
// });
