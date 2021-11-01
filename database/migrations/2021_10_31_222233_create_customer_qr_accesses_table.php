<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerQrAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_qr_accesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custOrdering_id');
            $table->unsignedBigInteger('mainCust_id');
            $table->unsignedBigInteger('subCust_id');
            $table->string('tableNumber');
            $table->string('status');
            $table->date('accessDate');
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
        Schema::dropIfExists('customer_qr_accesses');
    }
}
