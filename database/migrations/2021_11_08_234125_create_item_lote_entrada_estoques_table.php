<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemLoteEntradaEstoquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_lote_entrada_estoques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_lote');
            $table->bigInteger('cd_produto');
            $table->float('peso', 8, 2);
            $table->unsignedBigInteger('cd_usuario');
            $table->timestamps();

            $table->foreign('cd_lote')->references('id')->on('lote_entrada_estoques');
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
        Schema::dropIfExists('item_lote_entrada_estoques');
    }
}
