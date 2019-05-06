<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsUsersBoards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_boards', function (Blueprint $table) {
            $table->string('trello_board_id');
            $table->string('board_id');
            $table->boolean('is_admin');                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_boards', function (Blueprint $table) {
            $table->dropColumn('trello_board_id');
            $table->dropColumn('board_id');
            $table->dropColumn('is_admin');
        });
    }
}
