<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinhaMotoristaTableMotoristaVeiculo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorista_veiculos', function (Blueprint $table) {
            $table->unsignedBigInteger('cd_linha')->nullable()->after('cd_tipoveiculo');

            $table->foreign('cd_linha')->references('id')->on('linha_motoristas');
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
        // Schema::table('motorista_veiculos', function (Blueprint $table) {
        //     $table->dropColumn('cd_linha');
        // });
    }
}
