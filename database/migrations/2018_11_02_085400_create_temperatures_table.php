<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemperaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temperatures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('city_code');
            $table->integer('current_temperature');
            $table->integer('temp_first')->nullable();
            $table->integer('temp_second')->nullable();
            $table->integer('temp_third')->nullable();
            $table->integer('temp_fourth')->nullable();
            $table->integer('temp_fifth')->nullable();
            $table->integer('temp_sixth')->nullable();
            $table->integer('temp_seventh')->nullable();
            $table->integer('temp_eighth')->nullable();
            $table->integer('temp_ninth')->nullable();
            $table->integer('temp_tenth')->nullable();
            $table->integer('temp_eleventh')->nullable();
            $table->integer('temp_twelfth')->nullable();
            $table->integer('hour_id');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
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
        Schema::dropIfExists('temperatures');
    }
}
