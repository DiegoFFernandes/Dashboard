<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusTableAnaliseFrotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analise_frotas', function (Blueprint $table) {
            $table->string('status', 1)->after('placa'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analise_frotas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
