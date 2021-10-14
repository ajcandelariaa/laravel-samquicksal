<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_applicants', function (Blueprint $table) {
            $table->id();
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

            $table->string('rName');
            $table->string('rBranch');
            $table->string('rAddress');
            $table->string('rCity');
            $table->string('rPostalCode');
            $table->string('rState');
            $table->string('rCountry');

            $table->mediumText('bir');
            $table->mediumText('dti');
            $table->mediumText('mayorsPermit');
            $table->mediumText('staffValidId');
            $table->timestamps();
        });
        
        // Schema::rename('restaurant_applicants', 'restaurant_applicant');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_applicants');
    }
}
