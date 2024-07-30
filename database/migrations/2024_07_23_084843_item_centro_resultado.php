<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItemCentroResultado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_centro_resultado', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('cd_empresa_desp');
            $table->BigInteger('cd_centroresultado');
            $table->string('ds_centroresultado');
            $table->unsignedBigInteger('cd_subgrupo');            
            $table->decimal('orcamento', total: 12, places: 2)->nullable();
            $table->char('alterado', 1);
            $table->char('expurgo', 1);
            
            $table->foreign('cd_subgrupo')->references('id')->on('subgrupo_centro_resultado');

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
        Schema::dropIfExists('item_centro_resultado');
    }
}
