<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdiantamentoDespesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adiantamento_despesas', function (Blueprint $table) {
            $table->id('cd_adiantamento');
            $table->unsignedBigInteger('cd_user');
            $table->char('tp_despesa','1');
            $table->date('dt_despesa');
            $table->float('vl_consumido');
            $table->string('ds_observacao',2400);
            $table->char('st_visto','1');
            $table->timestamps();

            $table->foreign('cd_user')->references('id')->on('users');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adiantamento_despesas');
    }
}
