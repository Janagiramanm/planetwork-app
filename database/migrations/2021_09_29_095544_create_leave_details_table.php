<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('reason');
            $table->float('request_days');
            $table->float('available_days');
            $table->float('approved_days')->nullable();
            $table->date('approved_from')->nullable();
            $table->date('approved_to')->nullable();
            $table->string('leave_type')->nullable();
            $table->string('status')->default('pending');
            $table->string('reject_reason')->nullable(); 
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
        Schema::dropIfExists('leave_details');
    }
}
