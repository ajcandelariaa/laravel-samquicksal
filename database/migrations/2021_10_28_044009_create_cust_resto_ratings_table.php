<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustRestoRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cust_resto_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('restAcc_id');
            $table->unsignedBigInteger('custOrdering_id');
            $table->string('rating');
            $table->string('comment');
            $table->string('anonymous');
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
        Schema::dropIfExists('cust_resto_ratings');
    }
}
