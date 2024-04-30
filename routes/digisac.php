<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Digisac\DigiSacController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('digisac')->group(function () {
        Route::get('index', [DigiSacController::class, 'index'])->name('digisac.index');
    });
});
