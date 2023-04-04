<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTpProdutoTableLoteEntraEstoque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lote_entrada_estoques', function (Blueprint $table) {
            $table->unsignedBigInteger('id_subgrupo')->default(101)->after('tp_lote');
            $table->unsignedBigInteger('id_marca')->default(30)->after('id_subgrupo');

            $table->foreign('id_subgrupo')->references('id')->on('sub_grupos');
            $table->foreign('id_marca')->references('id')->on('marca_pneus');
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
            $table->dropForeign('lote_entrada_estoques_id_subgrupo_foreign');
            $table->dropForeign('lote_entrada_estoques_id_marca_foreign');
            $table->dropColumn('id_subgrupo');
            $table->dropColumn('id_marca');
        });
    }
}
