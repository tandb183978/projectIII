<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_user', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('mail_id');
            $table->boolean('deleted')->nullable();
            $table->boolean('favorite')->nullable();
            $table->boolean('important')->nullable();
            $table->string('label')->nullable();
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
        Schema::dropIfExists('mail_user');
    }
}
