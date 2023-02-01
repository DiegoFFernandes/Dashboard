<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivoPneusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivo_pneus', function (Blueprint $table) {
            $table->id();
            $table->string('ds_motivo', 60);
            $table->string('ds_causa', 5000)->nullable();
            $table->string('ds_recomendacoes', 5000)->nullable();
            $table->char('tp_motivo', 1);   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivo_pneus');
    }
}
