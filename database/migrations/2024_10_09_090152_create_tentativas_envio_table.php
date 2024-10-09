<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTentativasEnvioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tentativas_envio', function (Blueprint $table) {
            $table->id();
            $table->integer('CD_EMPRESA');
            $table->integer('NR_DOCUMENTO');
            $table->integer('NR_TENTATIVAS');
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
        Schema::dropIfExists('tentativas_envio');
    }
}
