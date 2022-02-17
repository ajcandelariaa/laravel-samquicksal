<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restAcc_id');
            $table->string('status');
            $table->string('available');
            $table->string('foodSetName');
            $table->mediumText('foodSetDescription');
            $table->decimal('foodSetPrice', 5, 2)->nullable();
            $table->string('foodSetImage');
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
        Schema::dropIfExists('food_sets');
    }
}
