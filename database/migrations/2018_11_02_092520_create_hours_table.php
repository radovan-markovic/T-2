<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hours', function (Blueprint $table) {
            $table->increments('id');
            $table->time('hour_first')->nullable();
            $table->time('hour_second')->nullable();
            $table->time('hour_third')->nullable();
            $table->time('hour_fourth')->nullable();
            $table->time('hour_fifth')->nullable();
            $table->time('hour_sixth')->nullable();
            $table->time('hour_seventh')->nullable();
            $table->time('hour_eighth')->nullable();
            $table->time('hour_ninth')->nullable();
            $table->time('hour_tenth')->nullable();
            $table->time('hour_eleventh')->nullable();
            $table->time('hour_twelfth')->nullable();
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
        Schema::dropIfExists('hours');
    }
}
