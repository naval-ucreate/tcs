<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BoardActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('board_id')->nullable();
            $table->unsignedInteger('to_list_id')->nullable();
            $table->unsignedInteger('from_list_id')->nullable();
            $table->unsignedInteger('card_id')->nullable();
            $table->unsignedInteger('member_id')->nullable();
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
        Schema::dropIfExists('board_activities');
    }
}
