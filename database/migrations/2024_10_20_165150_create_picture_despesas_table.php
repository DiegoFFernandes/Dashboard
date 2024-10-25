<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePictureDespesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picture_despesas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_adiantamento');
            $table->string('path', 200);
            $table->timestamps();

            $table->foreign('cd_adiantamento')->references('cd_adiantamento')->on('adiantamento_despesas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('picture_despesas');
    }
}
