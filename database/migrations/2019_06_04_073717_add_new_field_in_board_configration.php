<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldInBoardConfigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('board_configurations', function (Blueprint $table) {
            $table->string('lable_color', 50)->nullable();
            $table->unsignedInteger('checklist_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('board_configurations', function (Blueprint $table) {
              $table->dropColumn('lable_color');
              $table->dropColumn('checklist_type');
        });
    }
}
