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
            $table->id('id');
            $table->integer('resApp_id');
            $table->string('verified');
            $table->string('status');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('address');
            $table->string('city');
            $table->string('postalCode');
            $table->string('state');
            $table->string('country');
            $table->string('role');
            $table->date('birthDate');
            $table->string('gender');
            $table->string('contactNumber');
            $table->string('landlineNumber')->nullable();
            $table->string('emailAddress');

            $table->string('username');
            $table->string('password');

            $table->string('rName');
            $table->string('rBranch');
            $table->string('rAddress');
            $table->string('rCity');
            $table->string('rPostalCode');
            $table->string('rState');
            $table->string('rCountry');
           
            $table->string('rLatitudeLoc')->nullable();
            $table->string('rLongitudeLoc')->nullable();
            $table->integer('rRadius');

            $table->integer('rNumberOfTables');
            $table->integer('r2seater');
            $table->integer('r3seater');
            $table->integer('r4seater');
            $table->integer('r5seater');
            $table->integer('r6seater');
            $table->integer('r7seater');
            $table->integer('r8seater');
            $table->integer('r9seater');
            $table->integer('r10seater');
            $table->string('rGcashQrCodeImage')->nullable();
            $table->string('rLogo')->nullable();
            
            $table->integer('rTimeLimit');

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
