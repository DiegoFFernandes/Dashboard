<?php

use App\Http\Controllers\Admin\Producao\EpisController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|producao'])->group(function () {
    Route::prefix('producao')->group(function () {
        Route::get('controle-epis', [EpisController::class, 'index'])->name('epis.index');
    });
});
