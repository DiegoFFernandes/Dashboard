<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRespPortariaTableMovimentoVeiculos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimento_veiculos', function(Blueprint $table){
            $table->unsignedBigInteger('cd_resp_entrada')->after('observacao');
            $table->unsignedBigInteger('cd_resp_saida')->after('entrada');

            $table->foreign('cd_resp_entrada')->references('id')->on('users');
            $table->foreign('cd_resp_saida')->references('id')->on('users');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movimento_veiculos', function(Blueprint $table){
            $table->dropColumn('cd_resp_entrada');
            $table->dropColumn('cd_resp_saida');
        });
        
    }
}
