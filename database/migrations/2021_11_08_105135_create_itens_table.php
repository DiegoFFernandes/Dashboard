<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens', function (Blueprint $table) {
            $table->id();
            $table->string('cd_codbarraemb', 100)->nullable();
            $table->bigInteger('cd_item');
            $table->string('ds_item', 100);
            $table->float('ps_liquido', 8, 2)->nullable();
            $table->string('sg_unidmed', 2); 
            $table->unsignedBigInteger('cd_subgrupo');
            $table->unsignedBigInteger('cd_marca');
            $table->unsignedBigInteger('cd_usuario');
            $table->timestamps();

            $table->foreign('cd_usuario')->references('id')->on('users');
            $table->foreign('cd_marca')->references('id')->on('marca_pneus');
            $table->foreign('cd_subgrupo')->references('id')->on('sub_grupos');            
        });      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itens');
    }
}
