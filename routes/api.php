<?php

use App\Http\Controllers\Admin\RhGestor\RhGestorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/auth/login', [AuthController::class, 'login'])->name('api_auth');

Route::middleware(['ApiJwt', 'log.api.requests'])->group(function () {
    Route::prefix('rhgestor')->group(function () {
        Route::post('store/custo-pessoal', [RhGestorController::class, 'IndicadorFinanceiroAgrupado'])->name('rh-gestor-financeiro');
        Route::get('list-custo-pessoal', [RhGestorController::class, 'ListFinanceiroAgrupado'])->name('rh-gestor-list');
        Route::post('sum-custo-pessoal', [RhGestorController::class, 'SumFinanceiroAgrupado'])->name('rh-gestor-sum');
    });
});
