<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoletoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boleto', function (Blueprint $table) {
            $table->id();
            $table->integer('CD_EMPRESA');
            $table->integer('NR_LANCAMENTO');
            $table->integer('NR_DOCUMENTO');            
            $table->string('NM_PESSOA', 200);
            $table->string('NR_CNPJCPF', 200);
            $table->string('CD_FORMAPAGTO', 4);
            $table->date('DT_EMISSAO');
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
        Schema::dropIfExists('boleto');
    }
}
