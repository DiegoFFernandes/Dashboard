<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustoPessoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custo_pessoal', function (Blueprint $table) {
            $table->id();
            $table->string('comp', 8);
            $table->string('cpfcnpj', 20);            
            $table->decimal('folha', total: 8, places: 2)->nullable();
            $table->decimal('mao_obra', total: 8, places: 2)->nullable();
            $table->decimal('pro_labore', total: 8, places: 2)->nullable();
            $table->decimal('comissoes', total: 8, places: 2)->nullable();
            $table->decimal('hr_extras', total: 8, places: 2)->nullable();
            $table->decimal('rescisoes_indenizacoes', total: 8, places: 2)->nullable();
            $table->decimal('beneficios', total: 8, places: 2)->nullable();
            $table->decimal('comissoes_pj', total: 8, places: 2)->nullable();
            $table->decimal('farmacia', total: 8, places: 2)->nullable();
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
        Schema::dropIfExists('custo_pessoal');
    }
}
