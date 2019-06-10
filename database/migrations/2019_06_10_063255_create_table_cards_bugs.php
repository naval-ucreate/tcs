<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCardsBugs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_bugs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('board_id')->nullable();
            $table->unsignedInteger('card_id')->nullable();
            $table->unsignedInteger('total')->nullable();
            $table->unsignedInteger('type')->nullable()->comment = '1-> revert count 2-> bugs counts';
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
        Schema::dropIfExists('card_bugs');
    }
}
