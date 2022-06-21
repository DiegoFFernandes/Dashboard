<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisEtapaproducaopneusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epis_etapaproducaopneus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_epi');
            $table->unsignedBigInteger('id_etapaproducao');
            $table->timestamps();

            $table->foreign('id_epi')->references('id')->on('epis');
            $table->foreign('id_etapaproducao')->references('id')->on('etapasproducaopneus');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('epis_etapaproducaopneus');
    }
}
