<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtapaMaquinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etapa_maquinas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_empresa');
            $table->unsignedBigInteger('cd_etapa_producao')->nullable();
            $table->unsignedBigInteger('cd_maquina');
            $table->integer('cd_seq_maq');
            $table->integer('cd_barras');
            $table->timestamps();

            $table->foreign('cd_etapa_producao')->references('id')->on('etapasproducaopneus');
            $table->foreign('cd_maquina')->references('id')->on('maquinas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etapa_maquinas');
    }
}
