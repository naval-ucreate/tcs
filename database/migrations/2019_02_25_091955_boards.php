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
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('board_url')->nullable();
            $table->string('background_image')->nullable();
            $table->string('background_tile')->nullable();
            $table->string('background_brightness')->nullable();
            $table->string('background_bottom_color')->nullable();
            $table->string('background_top_color')->nullable();
            $table->string('can_be_enterprise')->nullable();
            $table->string('can_be_public')->nullable();
            $table->string('can_be_org')->nullable();
            $table->string('can_be_private')->nullable();
            $table->string('can_invite')->nullable();
            $table->string('total_members')->nullable();
            $table->string('image')->nullable();
            $table->string('owner_token')->nullable();
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
