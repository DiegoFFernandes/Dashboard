<?php

use App\Http\Controllers\PowerBi\PowerBiEmbeddedController;
use Illuminate\Support\Facades\Route;

/*Rotas sem autenticação*/
Route::prefix('indicadores')->group(function () {
    /*Rotas de acompanhamento de ordem */
    Route::get('inadimplencia/h^f7dbtz^E1tj8xAZ6aEy7gsQ4ReEBYdo', [PowerBiEmbeddedController::class,'indicadorInadimplencia'])->name('indicador.inadimplencia');  


});