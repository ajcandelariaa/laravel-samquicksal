<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustOffenseEachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cust_offense_eaches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custOffMain_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('restAcc_id');
            $table->unsignedBigInteger('book_id');
            $table->string('book_type');
            $table->string('offenseType');
            $table->foreign('custOffMain_id')->references('id')->on('cust_offense_mains')->onDelete('cascade');
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
        Schema::dropIfExists('cust_offense_eaches');
    }
}
