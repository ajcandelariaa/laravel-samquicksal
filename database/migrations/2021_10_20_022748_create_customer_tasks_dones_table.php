<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTasksDonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_tasks_dones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customerStampCard_id');
            $table->string('taskName');
            $table->date('taskAccomplishDate');
            $table->foreign('customer_id')->references('id')->on('customer_accounts')->onDelete('cascade');
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
        Schema::dropIfExists('customer_tasks_dones');
    }
}
