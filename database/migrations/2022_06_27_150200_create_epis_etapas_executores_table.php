<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisEtapasExecutoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epis_etapas_executores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_executor');
            $table->unsignedBigInteger('id_etapa');
            $table->unsignedBigInteger('id_epi');
            $table->string('uso', 10);
            $table->timestamps();

            $table->foreign('id_executor')->references('id')->on('executoretapas');
            $table->foreign('id_etapa')->references('id')->on('etapasproducaopneus');
            $table->foreign('id_epi')->references('id')->on('epis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('epis_etapas_executores');
    }
}
