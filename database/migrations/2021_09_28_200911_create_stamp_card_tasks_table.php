<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampCardTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_card_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stampCards_id');
            $table->unsignedBigInteger('restaurantTaskLists_id');
            $table->timestamps();
            $table->foreign('stampCards_id')->references('id')->on('stamp_cards')->onDelete('cascade');
            $table->foreign('restaurantTaskLists_id')->references('id')->on('restaurant_task_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stamp_card_tasks');
    }
}
