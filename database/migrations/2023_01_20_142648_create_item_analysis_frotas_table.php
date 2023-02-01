<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemAnalysisFrotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_analysis_frotas', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('id_analise');
            $table->unsignedBigInteger('id_posicao');
            $table->unsignedBigInteger('id_motivo_pneu');
            
            $table->string('fogo', 12);
            $table->bigInteger('id_modelo');
            $table->string('ds_modelo');
            $table->bigInteger('id_medida');
            $table->string('ds_medida');
            $table->bigInteger('pressao');
            $table->bigInteger('sulco');

            $table->timestamps();

            $table->foreign('id_posicao')->references('id')->on('posicao_pneus');
            $table->foreign('id_analise')->references('id')->on('analise_frotas');
            $table->foreign('id_motivo_pneu')->references('id')->on('motivo_pneus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_analysis_frotas');
    }
}
