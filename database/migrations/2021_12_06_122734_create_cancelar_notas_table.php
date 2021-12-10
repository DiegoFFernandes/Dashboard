<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancelarNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancelar_notas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_empresa');
            $table->bigInteger('nr_lancamento');
            $table->bigInteger('nr_nota');            
            $table->string('nr_cnpjcpf');            
            $table->string('nm_pessoa');
            //$table->string('nr_motivo');
            $table->string('motivo');
            $table->unsignedBigInteger('cd_requerente'); 
            $table->bigInteger('cd_autorizador')->nullable();           
            $table->string('observacao', 300)->nullable();
            $table->timestamps();

            $table->foreign('cd_requerente')->references('id')->on('users'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cancelar_notas');
    }
}
