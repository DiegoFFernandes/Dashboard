<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePneusOuroBgw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pneus_ouro_bgw', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('CD_EMP');
            $table->bigInteger('ORD_CODBTS');
            $table->bigInteger('ORD_NUMERO');
            $table->date('ORD_DATEMI');
            $table->time('ORD_HOREMI')->nullable();
            $table->bigInteger('NUM_NF');
            $table->date('DATA_NF');
            $table->string('CLI_CPF', 40);
            $table->string('CLI_NOME', 200);
            $table->string('CLI_CEP', 12);
            $table->string('CLI_LOGRAD', 60);
            $table->bigInteger('CLI_NUMERO');
            $table->string('CLI_COMPL', 60)->nullable();
            $table->string('CLI_BAIRRO', 60);
            $table->string('CLI_CIDADE', 60);
            $table->string('CLI_UF', 60);
            $table->string('CLI_EMAIL', 60);
            $table->string('CLI_TEL1', 15);
            $table->string('MEDIDA', 60);
            $table->string('BANDA', 60);
            $table->string('MATRICULA', 60);
            $table->string('NUM_FOGO', 12);
            $table->string('DOT', 12);
            $table->string('MARCA', 60);
            $table->string('MODELO', 60);
            $table->bigInteger('CICLOVIDA');
            $table->bigInteger('CHV_COLETA');
            $table->decimal('PRECO', 8, 2);
            $table->string('COD_I_CICLO', 2);
            $table->string('COD_I_MARCA', 2);
            $table->string('COD_I_MED', 60);
            $table->string('COD_I_BANDA', 60)->nullable();  
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
        Schema::dropIfExists('pneus_ouro_bgw');
    }
}
