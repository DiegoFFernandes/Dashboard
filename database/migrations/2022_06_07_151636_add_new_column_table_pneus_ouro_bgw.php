<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnTablePneusOuroBgw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pneus_ouro_bgw', function (Blueprint $table) {
            $table->string('DESENHOPNEU', 45)->after('BANDA');            
            // $table->timestamp('EMISSAONF')->after('DATA_NF')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pneus_ouro_bgw', function (Blueprint $table) {
            $table->dropColumn('DESENHOPNEU');
            // $table->dropColumn('EXPORTADO');
            // $table->dropColumn('EMISSAONF');
        });
    }
}
