<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->integer('cd_empresa');
            $table->string('name', 40);
            $table->string('cpf', 12)->unique();
            $table->unsignedBigInteger('cd_email');
            $table->string('endereco', '200')->nullable();
            $table->integer('numero')->nullable();
            $table->string('phone', 14);
            $table->unsignedBigInteger('cd_usuario');
            $table->timestamps();

            $table->foreign('cd_email')->references('id')->on('emails');
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
        Schema::dropIfExists('pessoas');
    }
}
