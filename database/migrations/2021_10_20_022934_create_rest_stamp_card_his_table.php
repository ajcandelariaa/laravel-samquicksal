<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestStampCardHisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rest_stamp_card_his', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restAcc_id');
            $table->integer('stampCapacity');
            $table->string('stampReward');
            $table->date('stampValidity');
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
        Schema::dropIfExists('rest_stamp_card_his');
    }
}
