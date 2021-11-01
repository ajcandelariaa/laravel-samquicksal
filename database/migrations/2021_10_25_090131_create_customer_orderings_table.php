<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOrderingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_orderings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custBook_id');
            $table->unsignedBigInteger('restAcc_id');
            $table->string('custName');
            $table->string('custBookType');
            $table->string('tableNumbers');
            $table->integer('availableQrAccess');
            $table->string('grantedAccess');
            $table->string('status');
            $table->date('orderingDate');
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
        Schema::dropIfExists('customer_orderings');
    }
}
