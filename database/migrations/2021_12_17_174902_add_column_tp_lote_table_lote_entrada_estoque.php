<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTpLoteTableLoteEntradaEstoque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lote_entrada_estoques', function (Blueprint $table) {
            $table->string('tp_lote', 20)->after('status');
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
            $table->dropColumn('tp_lote');
        });
    }
}
