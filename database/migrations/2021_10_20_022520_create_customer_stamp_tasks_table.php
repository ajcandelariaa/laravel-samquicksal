<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerStampTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_stamp_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customerStampCard_id');
            $table->string('taskName');
            $table->foreign('customerStampCard_id')->references('id')->on('customer_stamp_cards')->onDelete('cascade');
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
        Schema::dropIfExists('customer_stamp_tasks');
    }
}
