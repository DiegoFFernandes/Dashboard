<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cd_usuario'); 
            $table->bigInteger('cd_pessoa'); //Essa informação virá do banco de dados firebird
            $table->foreignId('cd_cadusuario');
            $table->timestamps();

            $table->foreign('cd_usuario')->references('id')->on('users'); 
            $table->foreign('cd_cadusuario')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendedores');
    }
}
