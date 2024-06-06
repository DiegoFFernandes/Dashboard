<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTableCustoPessoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custo_pessoal', function (Blueprint $table) {
            $table->decimal('vale_transporte', total: 8, places: 2)->nullable();
            $table->decimal('plano_saude', total: 8, places: 2)->nullable();
            $table->decimal('vale_alimentacao', total: 8, places: 2)->nullable();
            $table->decimal('plano_odonto', total: 8, places: 2)->nullable();
            $table->decimal('seguro_vida', total: 8, places: 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custo_pessoal', function (Blueprint $table) {
            $table->dropColumn('vale_transporte');
            $table->dropColumn('plano_saude');
            $table->dropColumn('vale_alimentacao');
            $table->dropColumn('plano_odonto');
            $table->dropColumn('seguro_vida');            
        });
    }
}
