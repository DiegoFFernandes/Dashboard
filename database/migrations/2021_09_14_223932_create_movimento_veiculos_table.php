<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimentoVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimento_veiculos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_empresa');
            $table->unsignedBigInteger('cd_motorista_veiculos');
            $table->unsignedBigInteger('cd_pessoa');
            $table->unsignedBigInteger('cd_linha');
            $table->text('observacao')->nullable();            
            $table->timestamp('entrada')->nullable();            
            $table->timestamp('saida')->nullable();
            $table->timestamps();

            $table->foreign('cd_pessoa')->references('id')->on('pessoas');
            $table->foreign('cd_motorista_veiculos')->references('id')->on('motorista_veiculos');  
            $table->foreign('cd_linha')->references('id')->on('linha_motoristas');            
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
    }
}
