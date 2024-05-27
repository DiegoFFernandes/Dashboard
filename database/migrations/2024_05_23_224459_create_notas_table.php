<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->integer('CD_EMPRESA');
            $table->integer('NR_LANCAMENTO');
            $table->string('TP_NOTA', 10);
            $table->string('CD_SERIE', 10);
            $table->integer('NR_NOTA');
            $table->integer('CD_PESSOA');
            $table->string('NM_PESSOA', 200);
            $table->string('NR_CNPJCPF', 200);            
            $table->char('STATUS', 1);


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
        Schema::dropIfExists('notas');
    }
}
