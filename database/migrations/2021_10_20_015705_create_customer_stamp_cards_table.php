<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerStampCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_stamp_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('restAcc_id');
            $table->string('status');
            $table->string('claimed');
            $table->integer('currentStamp');
            $table->integer('stampCapacity');
            $table->string('stampReward');
            $table->date('stampValidity');
            $table->foreign('customer_id')->references('id')->on('customer_accounts')->onDelete('cascade');
            $table->foreign('restAcc_id')->references('id')->on('restaurant_accounts')->onDelete('cascade');
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
        Schema::dropIfExists('customer_stamp_cards');
    }
}
