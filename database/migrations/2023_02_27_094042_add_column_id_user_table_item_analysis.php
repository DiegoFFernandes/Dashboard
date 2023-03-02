<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdUserTableItemAnalysis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_analysis_frotas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->default(1)->after('sulco');
            $table->string('dot', '10')->after('fogo')->nullable();
            $table->foreign('id_user')->references('id')->on('users');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_analysis_frotas', function (Blueprint $table) {
            $table->dropForeign('item_analysis_frotas_id_user_foreign');
            $table->dropColumn('id_user');
            $table->dropColumn('dot');
        });
    }
}
