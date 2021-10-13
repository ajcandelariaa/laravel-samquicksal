<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restAcc_id');
            $table->integer('stampCapacity');
            $table->unsignedBigInteger('stampReward_id');
            $table->date('stampValidity');
            $table->timestamps();
            $table->foreign('restAcc_id')->references('id')->on('restaurant_accounts')->onDelete('cascade');
            $table->foreign('stampReward_id')->references('id')->on('restaurant_reward_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stamp_cards');
    }
}
