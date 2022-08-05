<?php

use App\Http\Controllers\Admin\Procedimento\ProcedimentoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('procedimento')->group(function () {
        Route::get('listar-procedimentos', [ProcedimentoController::class, 'index'])->name('procedimento.index');
        Route::post('upload', [ProcedimentoController::class, 'upload'])->name('procedimento.upload');
    
    });
});
