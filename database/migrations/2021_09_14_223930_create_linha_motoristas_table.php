<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinhaMotoristasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linha_motoristas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_empresa');
            $table->string('linha')->nullable();
            $table->char('ativa', 1)->nullable();
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
        Schema::dropIfExists('movimento_veiculos');
        Schema::dropIfExists('linha_motoristas');
    }
}
