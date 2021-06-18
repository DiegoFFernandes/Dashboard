<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeloveiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modeloveiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_marca');
            $table->unsignedBigInteger('cd_frotaveiculos');
            $table->string('descricao', 30);
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
        Schema::dropIfExists('modeloveiculos');
    }
}
