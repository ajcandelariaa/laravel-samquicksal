<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_l_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custOrdering_id');
            $table->unsignedBigInteger('cust_id');
            $table->string('tableNumber');
            $table->unsignedBigInteger('foodItem_id');
            $table->string('foodItemName');
            $table->integer('quantity');
            $table->decimal('price', 5, 2);
            $table->string('orderDone');
            $table->dateTIme('orderSubmitDT')->nullable();
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
        Schema::dropIfExists('customer_l_orders');
    }
}
