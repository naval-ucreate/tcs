<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BoardConfigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('board_id')->nullable();
            $table->unsignedInteger('list_id')->nullable();
            $table->unsignedInteger('type')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('board_configurations');
    }
}
