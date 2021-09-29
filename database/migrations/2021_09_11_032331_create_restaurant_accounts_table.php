<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('restaurant_applicants_id');
            $table->string('verified');
            $table->string('status');
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('sname');
            $table->string('address');
            $table->string('role');
            $table->date('birthDate');
            $table->string('contactNumber');
            $table->string('landlineNumber');
            $table->string('emailAddress');
            $table->string('username');
            $table->string('password');
            $table->string('restaurantName');
            $table->string('branchName');
            $table->string('fullAddress');
            $table->string('city');
            $table->string('restaurantExactLocation');
            // $table->string('restaurantLatitudeLoc');
            // $table->string('restaurantLongitudeLoc');
            $table->integer('numberOfTables');
            $table->integer('capacityPerTable');
            $table->string('gcashQrCodeImage');
            $table->string('restaurantLogo');
            $table->string('bir');
            $table->string('dti');
            $table->string('mayorsPermit');
            $table->string('staffValidId');
            $table->timestamps();
        });

        // Schema::rename('restaurant_accounts', 'restaurant_account');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_accounts');
    }
}
