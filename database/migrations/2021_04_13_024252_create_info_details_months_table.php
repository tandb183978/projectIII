<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoDetailsMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_details_months', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_id');
            $table->string('month');
            $table->integer('number_day');
            $table->integer('number_dayon')->default('0');
            $table->integer('number_dayoff')->default('0');
            $table->integer('number_dayleft')->nullable();
            $table->double('overtime_workings')->nullable();
            $table->double('undertime_workings')->nullable();
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
        Schema::dropIfExists('info_details_months');
    }
}
