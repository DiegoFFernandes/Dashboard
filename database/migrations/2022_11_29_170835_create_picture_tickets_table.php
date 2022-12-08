<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePictureTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picture_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_tickets');
            $table->string('path', 200);
            $table->timestamps();

            $table->foreign('cd_tickets')->references('id')->on('tickets');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('picture_tickets');
    }
}
