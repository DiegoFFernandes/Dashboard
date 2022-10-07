<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsCdusuarioAndTpusuarioTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('cd_pessoa')->after('empresa')->nullable();
            $table->string('ds_tipopessoa', 60)->default('Funcionario')->after('cd_pessoa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cd_pessoa');
            $table->dropColumn('ds_tipopessoa');
        });
    }
}
