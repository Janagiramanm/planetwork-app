<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('emp_code')->nullable();
            $table->string('designation')->nullable();
            $table->string('date_of_join')->nullable();
            $table->string('basic_pay')->nullable();
            $table->string('hra')->nullable();
            $table->string('conveyance')->nullable();
            $table->string('gratuity_pay')->nullable();
            $table->string('special_allowance')->nullable();
            $table->string('variable_incentive')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->unsignedBigInteger('city_id');
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
        Schema::dropIfExists('employee_details');
    }
}
