<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRhgestorIndicadorFinanceiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rhgestor_indicador_financeiro', function (Blueprint $table) {
            $table->id();
            $table->string('comp', 8);
            $table->BigInteger('cd_indicador');
            $table->unsignedBigInteger('cd_area_lotacao');
            $table->decimal('valor', total: 12, places: 2)->nullable();
            
            $table->foreign('cd_area_lotacao')->references('cd_area')->on('area_lotacao');
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
        Schema::dropIfExists('rhgestor_indicador_financeiro');
    }
}
