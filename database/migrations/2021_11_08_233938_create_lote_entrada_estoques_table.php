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
            $table->bigInteger('cd_ordemcompra')->nullable();
            $table->bigInteger('qtd_itens')->nullable();
            $table->float('ps_liquido_total', 8, 2)->nullable();  
            $table->char('status', 1);         
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
