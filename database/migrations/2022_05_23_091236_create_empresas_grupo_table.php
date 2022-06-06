<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_grupo', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_empresa');
            $table->string('ds_local', 40);
            $table->bigInteger('cd_loja');
            $table->bigInteger('cd_grupo');
            $table->bigInteger('regiao');
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
        Schema::dropIfExists('empresas_grupo');
    }
}
