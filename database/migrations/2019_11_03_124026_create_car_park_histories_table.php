<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarParkHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_park_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('car_park_booking_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('count');
            $table->string('date_time');
            $table->string('vehicle_no');
            $table->string('amount');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('car_park_booking_id')->references('id')->on('car_park_bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_park_histories');
    }
}
