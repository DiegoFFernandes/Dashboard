<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_setor');
            $table->string('title', 200);
            $table->string('description', 600);
            $table->string('path', 200);
            $table->char('status', 1);
            $table->unsignedBigInteger('id_user_create');
            
            $table->foreign('id_setor')->references('id')->on('setors');
            $table->foreign('id_user_create')->references('id')->on('users');

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
        Schema::dropIfExists('procedimentos');
    }
}
