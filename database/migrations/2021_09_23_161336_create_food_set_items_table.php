<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodSetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_set_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('foodSet_id');
            $table->unsignedBigInteger('foodItem_id');
            $table->timestamps();
            $table->foreign('foodSet_id')->references('id')->on('food_sets')->onDelete('cascade');
            $table->foreign('foodItem_id')->references('id')->on('food_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_set_items');
    }
}
