<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_id');
            $table->date('date');
            $table->text('user_name')->nullable();
            $table->text('job_name')->nullable();
            $table->text('customer_name')->nullable();
            $table->text('sr_no')->nullable();
            $table->float('travel_distance');
            $table->text('from_address')->nullable();
            $table->text('to_address')->nullable();
            $table->text('start')->nullable();
            $table->text('end')->nullable();
            $table->text('status')->nullable();
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
        Schema::dropIfExists('work_reports');
    }
}
