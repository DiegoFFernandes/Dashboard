<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubgrupoCentroResultado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subgrupo_centro_resultado', function (Blueprint $table) {
            $table->id();
            $table->string('ds_subgrupo');
            $table->unsignedBigInteger('cd_grupo');
            $table->string('ds_tipo');
            $table->BigInteger('cd_dre')->nullable();
            
            $table->foreign('cd_grupo')->references('id')->on('grupo_centro_resultado');            
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
        Schema::dropIfExists('subgrupo_centro_resultado');
    }
}
