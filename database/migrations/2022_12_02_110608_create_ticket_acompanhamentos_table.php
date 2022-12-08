<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketAcompanhamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_acompanhamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_ticket');
            $table->unsignedBigInteger('cd_user_create');
            $table->unsignedBigInteger('cd_user_resp')->nullable();
            $table->string('message', 600);
            $table->char('type', 1);

            $table->timestamps();
            
            $table->foreign('cd_ticket')->references('id')->on('tickets');
            $table->foreign('cd_user_create')->references('id')->on('users');
            $table->foreign('cd_user_resp')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_acompanhamentos');
    }
}
