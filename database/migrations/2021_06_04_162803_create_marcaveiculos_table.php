<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarcaveiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marcaveiculos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_marca');
            $table->string('descricao', 30);
            $table->unsignedBigInteger('cd_frotaveiculos');
            $table->unsignedBigInteger('cd_usuario');
            $table->timestamps();
            
            $table->foreign('cd_frotaveiculos')->references('id')->on('frotaveiculos');
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
        Schema::dropIfExists('motorista_veiculos');
        Schema::dropIfExists('marcaveiculos');
        Schema::dropIfExists('modeloveiculos');
    }
}
