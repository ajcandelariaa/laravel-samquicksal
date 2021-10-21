<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestStampTasksHisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rest_stamp_tasks_his', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restStampCardHis_id');
            $table->string('taskName');
            $table->foreign('restStampCardHis_id')->references('id')->on('rest_stamp_card_his')->onDelete('cascade');
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
        Schema::dropIfExists('rest_stamp_tasks_his');
    }
}
