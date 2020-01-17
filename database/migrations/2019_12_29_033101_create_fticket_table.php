<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFticketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fticket', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_code');
            $table->foreign('event_code')->references('event_code')->on('events')->onDelete('cascade');
            $table->string('instrument_category');
            $table->string('member_name')->nullable();
            $table->string('tickets_name');
            $table->string('contact')->nullable();
            $table->integer('number');
            $table->integer('status');
            $table->string('get_tickets_code');
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
        Schema::dropIfExists('fticket');
    }
}
