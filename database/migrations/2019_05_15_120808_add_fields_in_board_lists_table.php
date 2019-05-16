<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInBoardListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('board_lists', function (Blueprint $table) {
            $table->boolean('checklist_enable')->nullable();
            $table->boolean('bug_enable')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('board_lists', function (Blueprint $table) {
            $table->dropColumn('checklist_enable');
            $table->dropColumn('bug_enable');
        });
    }
}
