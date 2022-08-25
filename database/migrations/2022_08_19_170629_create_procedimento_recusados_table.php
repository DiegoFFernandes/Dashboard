<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcedimentoRecusadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimento_recusados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_procedimento');
            $table->unsignedBigInteger('id_user_create');
            $table->unsignedBigInteger('id_user_approver');
            $table->string('message', 600);
            $table->char('type', 1);

            $table->foreign('id_procedimento')->references('id')->on('procedimentos');
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->foreign('id_user_approver')->references('id')->on('users');

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
        Schema::dropIfExists('procedimento_recusados');
    }
}
