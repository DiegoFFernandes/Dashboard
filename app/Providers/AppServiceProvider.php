<?php

namespace App\Providers;

use App\Models\MovimentoVeiculo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    
    public function boot()
    {
        Schema::defaultStringLength(191);

        /* Inicia o envio das variaveis da portaria referindo-se a quantidades de entrada e saida*/
        $dtInicio = date('Y-m-d 00:00:00');
        $entrada = 'entrada';
        $saida = 'saida';
        
        $movimento = new MovimentoVeiculo();
        $qtdEntrada =  $movimento->qtdMovimento($entrada, $dtInicio);
        $qtdSaida =  $movimento->qtdMovimento($saida, $dtInicio);

        View::share('qtdEntrada', $qtdEntrada);
        View::share('qtdSaida', $qtdSaida);

        /*fim variaveis portaria*/
    }
}
