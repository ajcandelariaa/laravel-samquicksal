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
            $table->increments('id');
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
            $table->string('emailAddress');
            $table->string('restaurantName');
            $table->string('branchName');
            $table->string('fullAddress');
            $table->string('city');
            $table->string('bir');
            $table->string('dti');
            $table->string('mayorsPermit');
            $table->string('staffValidId');
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
