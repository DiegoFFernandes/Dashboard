<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogApiNewAgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_api_new_ages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cd_empresa',)->nullable();
            $table->bigInteger('ordem');
            $table->bigInteger('pedido')->nullable();
            $table->string('ocorrencia', '1000')->nullable();              
            $table->string('exportado', 2);          
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
        Schema::dropIfExists('log_api_new_ages');
    }
}
