<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_l_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custOrdering_id');
            $table->string('tableNumber');
            $table->string('request');
            $table->string('requestDone');
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
        Schema::dropIfExists('customer_l_requests');
    }
}
