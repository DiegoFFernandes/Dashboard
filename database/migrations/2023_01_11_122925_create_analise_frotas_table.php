<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnaliseFrotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analise_frotas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_pessoa');
            $table->string('nm_pessoa', 256);
            $table->string('placa', 7);
            $table->unsignedBigInteger('marca_modelo');
            $table->bigInteger('sulco');
            $table->bigInteger('ps_min');
            $table->bigInteger('ps_max');
            $table->string('observacao', 256);
            $table->timestamps();

            $table->foreign('marca_modelo')->references('id')->on('marca_modelo_frotas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analise_frotas');
    }
}
