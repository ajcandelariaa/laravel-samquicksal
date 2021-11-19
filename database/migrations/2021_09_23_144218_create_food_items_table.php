<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restAcc_id');
            $table->string('status');
            $table->string('available');
            $table->string('foodItemName');
            $table->mediumText('foodItemDescription');
            $table->decimal('foodItemPrice', 5, 2);
            $table->string('foodItemImage');
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
        Schema::dropIfExists('food_items');
    }
}
