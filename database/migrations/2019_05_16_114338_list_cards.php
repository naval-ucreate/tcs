<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ListCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('board_id');     
            $table->unsignedInteger('list_id');
            $table->string('trello_card_id',50);
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('total_bugs')->nullable();
            $table->unsignedInteger('total_return')->nullable();
            $table->boolean('is_complete')->nullable();
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
        Schema::dropIfExists('list_cards');
    }
}
