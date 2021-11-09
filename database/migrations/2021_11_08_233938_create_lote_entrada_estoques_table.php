<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoteEntradaEstoquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote_entrada_estoques', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 30); 
            $table->bigInteger('cd_ordemcompra');
            $table->unsignedBigInteger('cd_usuario');
            $table->timestamps();

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
        Schema::dropIfExists('lote_entrada_estoques');
    }
}
