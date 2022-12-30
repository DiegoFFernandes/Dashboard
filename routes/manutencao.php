<?php

use App\Http\Controllers\Admin\Manutencao\ManutencaoController;
use App\Http\Controllers\Admin\Manutencao\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|manutencao|coordenador'])->group(function () {
    Route::prefix('manutencao')->group(function () {
        Route::get('index', [ManutencaoController::class, 'index'])->name('manutencao.index');
        Route::post('store', [ManutencaoController::class, 'store'])->name('manutencao.store');

        Route::get('get-tickets', [ManutencaoController::class, 'getTickets'])->name('manutencao.get-tickets');
        Route::get('chat-tickets', [ManutencaoController::class, 'chatTickets'])->name('manutencao.chat');
        Route::post('status-chamado', [ManutencaoController::class, 'statusChamado'])->name('manutencao.status');
        Route::post('reopen-chamado', [ManutencaoController::class, 'reOpen'])->name('manutencao.reopen');
        Route::post('view-pictures-tickets', [ManutencaoController::class, 'viewPictures'])->name('manutencao.pictures');

        Route::get('maquinas', [ManutencaoController::class, 'machines'])->name('manutencao.machines');        
        Route::get('associar-etapas', [ManutencaoController::class, 'associatePhases'])->name('manutencao.associate-phases');        
        Route::post('editar-etapas', [ManutencaoController::class, 'editPhases'])->name('manutencao.edit-phases'); 
        Route::post('update-etapas', [ManutencaoController::class, 'updatePhases'])->name('manutencao.update-phases');   

        // Route::get('send-wpp', [ManutencaoController::class, 'SendWpp']); 

        Route::get('relatorio', [ReportController::class, 'index'])->name('manutencao-report'); 

        //ajax report
        Route::get('defeito', [ReportController::class, 'tpProblem'])->name('manutencao.problem'); 
        Route::get('tempo-chamados', [ReportController::class, 'timeTickets'])->name('manutencao.time-tickets'); 
        Route::get('media-tempo-chamados', [ReportController::class, 'averageTickets'])->name('manutencao.average-tickets');
    });
});
