<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEticketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eticket', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_code');
            $table->foreign('event_code')->references('event_code')->on('events');
            $table->string('email');
            $table->string('name');
            $table->string('phone');
            $table->integer('number');
            $table->string('code');
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
        Schema::dropIfExists('eticket');
    }
}
