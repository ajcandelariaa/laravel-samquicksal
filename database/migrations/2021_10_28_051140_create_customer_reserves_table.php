<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_reserves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('restAcc_id');
            $table->unsignedBigInteger('orderSet_id');
            $table->string('status');
            $table->date('reserveDate');
            $table->string('reserveTime');
            $table->integer('numberOfPersons');
            $table->integer('numberOfTables');
            $table->integer('hoursOfStay');
            $table->integer('numberOfChildren');
            $table->integer('numberOfPwd');
            $table->integer('totalPwdChild');
            $table->string('notes')->nullable();
            $table->string('rewardStatus')->nullable();
            $table->string('rewardType')->nullable();
            $table->integer('rewardInput')->nullable();
            $table->string('rewardClaimed')->nullable();
            $table->decimal('totalPrice', 10, 2);
            $table->integer('childrenDiscount')->nullable();
            $table->decimal('additionalDiscount', 10, 2)->nullable();
            $table->decimal('promoDiscount', 10, 2)->nullable();
            $table->decimal('offenseCharges', 10, 2)->nullable();
            $table->dateTIme('approvedDateTime')->nullable();
            $table->dateTIme('cancelDateTime')->nullable();
            $table->dateTIme('declinedDateTime')->nullable();
            $table->dateTIme('validationDateTime')->nullable();
            $table->dateTIme('tableSettingDateTime')->nullable();
            $table->dateTIme('eatingDateTime')->nullable();
            $table->dateTIme('checkoutDateTime')->nullable();
            $table->dateTIme('completeDateTime')->nullable();
            $table->string('declinedReason')->nullable();
            $table->string('cancelReason')->nullable();
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
        Schema::dropIfExists('customer_reserves');
    }
}
