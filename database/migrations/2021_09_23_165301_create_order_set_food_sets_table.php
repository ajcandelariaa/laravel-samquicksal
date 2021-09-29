<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSetFoodSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_set_food_sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderSet_id');
            $table->unsignedBigInteger('foodSet_id');
            $table->timestamps();
            $table->foreign('orderSet_id')->references('id')->on('order_sets')->onDelete('cascade');
            $table->foreign('foodSet_id')->references('id')->on('food_sets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_set_food_sets');
    }
}
