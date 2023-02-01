<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePictureAnalysisFrotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picture_analysis_frotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_item_analysis');
            $table->string('path', 200);
            $table->timestamps();

            $table->foreign('id_item_analysis')->references('id')->on('item_analysis_frotas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('picture_analysis_frotas');
    }
}
