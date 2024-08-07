<?php

use App\Http\Controllers\Admin\Cobranca\CobrancaController;
use App\Http\Controllers\Admin\Digisac\DigiSacController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|cobranca'])->group(function () {
    Route::prefix('cobranca')->group(function () {
        Route::get('index', [CobrancaController::class, 'index'])->name('cobranca.index');
        Route::get('detalhe-agenda/usuario/{cdusuario}/data/{dt}', [CobrancaController::class, 'DetalheAgenda'])->name('cobranca.detalhe-agenda');
        Route::get('clientes-novos/usuario/{cdusuario}/data/{dt}', [CobrancaController::class, 'ClientesNovos'])->name('cobranca.clientes-novos');
        Route::get('agenda/data', [CobrancaController::class, 'AgendaData'])->name('cobranca.agenda.mes');
        Route::get('clientes-novos/data', [CobrancaController::class, 'ClientesNovosMes'])->name('cobranca.clientes-novos-mes');
        Route::get('envio-follow-up', [CobrancaController::class, 'searchEnvio'])->name('search-envio');
        Route::get('get-search-follow', [CobrancaController::class, 'getSearchEnvio'])->name('get-search-envio');
        Route::get('get-email-follow/{id}', [CobrancaController::class, 'getEmailEnvio'])->name('get-email-follow');
        Route::post('reenvia-follow', [CobrancaController::class, 'reenviaFollow'])->name('reenvia-follow');
        Route::get('get-envio-iagente', [CobrancaController::class, 'getSubmitIagente'])->name('get-envio-iagente');
        Route::delete('delete-email-webhook-iagente', [CobrancaController::class, 'DeleteEmailWebhookIagente'])->name('delete-email-webhook-iagente');


        Route::get('chart-data', [CobrancaController::class, 'chartLineAjax'])->name('cobranca.chart-api');
        Route::get('get-qtd-clients-novos', [CobrancaController::class, 'qtdClientesNovosMes'])->name('get-qtd-clients-novos');
        Route::get('get-fp-clients-novos', [CobrancaController::class, 'listClientFormPgto'])->name('get-fp-clients-novos');



        Route::get('list-envio-notas', [DigiSacController::class, 'ListEnvioNotaDigisac'])->name('list-envio-nota-digisac');
    });
});