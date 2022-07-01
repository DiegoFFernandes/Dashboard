<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecutoretapasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('executoretapas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('matricula');
            $table->string('nmexecutor', 60);
            $table->bigInteger('cd_empresa')->nullable();
            $table->string('localizacao', 60);
            $table->string('stativo', 2);
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
        Schema::dropIfExists('executoretapas');
    }
}
