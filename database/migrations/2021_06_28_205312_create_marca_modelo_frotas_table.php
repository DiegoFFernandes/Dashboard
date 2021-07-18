<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarcaModeloFrotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marca_modelo_frotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_marca');
            $table->unsignedBigInteger('cd_modelo');
            $table->unsignedBigInteger('cd_frota');
            $table->unsignedBigInteger('cd_usuario');
            $table->timestamps();

            $table->foreign('cd_marca')->references('id')->on('marcaveiculos');
            $table->foreign('cd_modelo')->references('id')->on('modeloveiculos');
            $table->foreign('cd_frota')->references('id')->on('frotaveiculos');
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
        Schema::dropIfExists('marca_modelo_frotas');
    }
}
