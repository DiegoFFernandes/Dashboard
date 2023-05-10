<?php

namespace App\Providers;

use App\Models\MovimentoVeiculo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
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

        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();
            return in_array($extension, $parameters);
        });
       
        // /* Inicia o envio das variaveis da portaria referindo-se a quantidades de entrada e saida*/
        $dtInicio = date('Y-m-d 00:00:00');
        $entrada = 'entrada';
        $saida = 'saida';
        
        $movimento = new MovimentoVeiculo();
        $qtdEntrada =  $movimento->qtdMovimento($entrada, $dtInicio);
        $qtdSaida =  $movimento->qtdMovimento($saida, $dtInicio);

        
        View::share(compact('qtdEntrada', 'qtdSaida'));

        /*fim variaveis portaria*/
    }
}
