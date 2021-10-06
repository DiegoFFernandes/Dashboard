<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotoristaVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorista_veiculos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_empresa');
            $table->unsignedBigInteger('cd_pessoa');
            $table->string('placa', 7);
            $table->string('cor', 30);
            $table->unsignedBigInteger('cd_marcamodelofrota');            
            $table->bigInteger('ano');
            $table->unsignedBigInteger('cd_tipoveiculo');
            $table->char('ativo', 1);            
            $table->unsignedBigInteger('cd_usuario');
            $table->timestamps();

            $table->foreign('cd_pessoa')->references('id')->on('pessoas');
            $table->foreign('cd_marcamodelofrota')->references('id')->on('marca_modelo_frotas');            
            $table->foreign('cd_tipoveiculo')->references('id')->on('tipoveiculo');
            $table->foreign('cd_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::dropIfExists('movimento_veiculos');        
        Schema::dropIfExists('motorista_veiculos');        
    }
}
