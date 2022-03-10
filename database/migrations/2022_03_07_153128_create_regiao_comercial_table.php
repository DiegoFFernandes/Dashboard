<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegiaoComercialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regiao_comercial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cd_usuario'); 
            $table->bigInteger('cd_regiaocomercial'); //Essa informação virá do banco de dados firebird
            $table->string('ds_regiaocomercial', 40);
            $table->foreignId('cd_cadusuario');
            
            $table->foreign('cd_usuario')->references('id')->on('users'); 
            $table->foreign('cd_cadusuario')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regiao_comercial');
    }
}
