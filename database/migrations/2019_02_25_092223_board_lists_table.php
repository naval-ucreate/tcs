<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BoardListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trello_board_id',50);     
            $table->string('trello_list_id',50);
            $table->string('name');
            $table->string('web_hook_id')->nullable();
<<<<<<< HEAD
            $table->boolean('web_hook_enable')->nullable();
=======
            $table->enum('web_hook_enable',[true, false])->nullable();
>>>>>>> cf9c6c535e3b5c5f69f8eb43d332e5e74ce61e8d
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
        Schema::dropIfExists('board_lists');
    }
}
