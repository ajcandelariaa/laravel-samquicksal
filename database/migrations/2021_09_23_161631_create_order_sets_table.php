<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restAcc_id');
            $table->string('status');
            $table->string('available');
            $table->string('orderSetName');
            $table->mediumText('orderSetTagline');
            $table->mediumText('orderSetDescription');
            $table->decimal('orderSetPrice', 5, 2);
            $table->string('orderSetImage');
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
        Schema::dropIfExists('order_sets');
    }
}
