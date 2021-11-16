<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnavailableDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unavailable_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restAcc_id');
            $table->date('unavailableDatesDate');
            $table->string('startTime');
            $table->mediumText('unavailableDatesDesc');
            $table->timestamps();
            $table->foreign('restAcc_id')->references('id')->on('restaurant_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unavailable_dates');
    }
}
