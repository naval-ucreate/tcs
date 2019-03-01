<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Boards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trello_user_id');
            $table->integer('user_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('board_url')->nullable();
            $table->string('backgroundImage')->nullable();
            $table->string('backgroundTile')->nullable();
            $table->string('backgroundBrightness')->nullable();
            $table->string('backgroundBottomColor')->nullable();
            $table->string('backgroundTopColor')->nullable();
            $table->string('canBeEnterprise')->nullable();
            $table->string('canBePublic')->nullable();
            $table->string('canBeOrg')->nullable();
            $table->string('canBePrivate')->nullable();
            $table->string('canInvite')->nullable();
            $table->string('total_members')->nullable();
            $table->string('image')->nullable();
            $table->string('trello_board_id',50);
            $table->text('members'); // stroe in json .
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
        Schema::dropIfExists('boards');
    }
}
