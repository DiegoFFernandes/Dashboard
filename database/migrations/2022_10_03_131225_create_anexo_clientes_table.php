<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnexoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexo_clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_pessoa');
            $table->unsignedBigInteger('nr_documento');
            $table->string('nm_pessoa', 200);
            $table->unsignedBigInteger('nr_contexto');
            $table->string('ds_contexto', 200);
            $table->string('path', 200);
            $table->timestamps();

            // $table->foreign('cd_pessoa')->references('cd_pessoa')->on('users');
        

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anexo_clientes');
    }
}
