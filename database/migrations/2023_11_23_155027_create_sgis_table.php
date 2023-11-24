<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSgisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sgis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_empresa');
            $table->string('title', 200);
            $table->string('description', 600);
            $table->string('path', 200);
            $table->date('dt_validade');
            $table->char('status', 1);
            $table->unsignedBigInteger('id_user_create');

            $table->foreign('cd_empresa')->references('cd_empresa')->on('empresas_grupo');
            $table->foreign('id_user_create')->references('id')->on('users');
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
        Schema::dropIfExists('sgis');
    }
}
