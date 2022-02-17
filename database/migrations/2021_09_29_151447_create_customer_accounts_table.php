<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('emailAddress');
            $table->string('emailAddressVerified');
            $table->string('contactNumber');
            $table->string('contactNumberVerified');
            $table->string('password');
            $table->mediumText('deviceToken');
            $table->string('profileImage')->nullable();
            $table->string('loggedIn')->nullable();
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
        Schema::dropIfExists('customer_accounts');
    }
}
