<?php

use App\Http\Controllers\Admin\Client\ClientAnexoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|acesso-cliente'])->group(function () {
    Route::prefix('area-do-cliente')->group(function () {
        Route::get('informacoes-para-empresa', [ClientAnexoController::class, 'index'])->name('cliente.dados-gerados-empresa.index');
        Route::get('view-tickets-pendents-empresa', [ClientAnexoController::class, 'getTickesPendents'])->name('client.tickets-pendents-enterprise');
        Route::get('save-tickets-pdf', [ClientAnexoController::class, 'saveTickets'])->name('client-save-tickets');
        Route::get('invoice', [ClientAnexoController::class, 'InvoiceClient'])->name('client-invoice');
        
    });
});

