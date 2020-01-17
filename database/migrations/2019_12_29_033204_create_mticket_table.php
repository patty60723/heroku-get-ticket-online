<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMticketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mticket', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_code');
            $table->foreign('event_code')->references('event_code')->on('events')->onDelete('cascade');
            $table->string('instrument_category');
            $table->string('member_name');
            $table->integer('number');
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
        Schema::dropIfExists('mticket');
    }
}
