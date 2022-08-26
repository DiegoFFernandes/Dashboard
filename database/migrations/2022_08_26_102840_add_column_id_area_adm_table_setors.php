<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdAreaAdmTableSetors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setors', function (Blueprint $table) {
            $table->unsignedBigInteger('id_area_adm')->default(1)->after('nm_setor');

            $table->foreign('id_area_adm')->references('id')->on('area_administrativas');            
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setors', function (Blueprint $table) {
            $table->dropForeign('setors_id_area_adm_foreign');
            $table->dropColumn('id_area_adm');
        });
    }
}
