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
            $table->string('board_id',50)->nullable();
            $table->string('to_list_id',50)->nullable();
            $table->string('from_list_id',50)->nullable();
            $table->string('card_id',50)->nullable();
            $table->string('user_id',50)->nullable();
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
