<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcedimentoAprovadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimento_aprovadors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_procedimento');
            $table->unsignedBigInteger('id_user');
            $table->char('aprovado', 1);

            $table->foreign('id_procedimento')->references('id')->on('procedimentos');
            $table->foreign('id_user')->references('id')->on('users');

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
        Schema::dropIfExists('procedimento_aprovadors');
    }
}
