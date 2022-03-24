<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('month')->nullable();
            $table->string('toAdmin')->nullable();
            $table->string('reason');
            $table->text('description');
            $table->string('multidays');
            $table->dateTime('leave_day')->nullable();
            $table->dateTime('start_leave_day')->nullable();
            $table->dateTime('end_leave_day')->nullable();
            $table->integer('number_day')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('leaves');
    }
}
