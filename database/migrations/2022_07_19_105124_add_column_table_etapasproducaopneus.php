<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTableEtapasproducaopneus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('etapasproducaopneus', function (Blueprint $table) {
            $table->string('nm_tabela', 60)->after('dsetapaempresa');
            $table->bigInteger('cd_etapa')->after('nm_tabela');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etapasproducaopneus', function (Blueprint $table) {
            $table->dropColumn('cd_etapa');
            $table->dropColumn('nm_tabela');
        });
    }
}
