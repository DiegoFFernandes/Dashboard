<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTableLoteEntradaEstoques extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lote_entrada_estoques', function (Blueprint $table) {
            $table->bigInteger('cd_empresa')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lote_entrada_estoques', function (Blueprint $table) {
            $table->dropColumn('cd_empresa');
        });
    }
}
