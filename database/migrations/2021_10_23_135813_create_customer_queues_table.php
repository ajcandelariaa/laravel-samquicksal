<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_queues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('restAcc_id');
            $table->unsignedBigInteger('orderSet_id');
            $table->string('status');
            $table->string('cancellable');
            $table->integer('numberOfPersons');
            $table->integer('numberOfTables');
            $table->integer('hoursOfStay');
            $table->integer('numberOfChildren');
            $table->integer('numberOfPwd');
            $table->integer('totalPwdChild');
            $table->string('notes');
            $table->string('rewardStatus');
            $table->string('rewardType');
            $table->integer('rewardInput');
            $table->decimal('totalPrice', 5, 2);
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
        Schema::dropIfExists('customer_queues');
    }
}
