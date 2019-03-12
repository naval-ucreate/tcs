<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    //
    protected $table = 'board_lists';

    protected $fillable = ['trello_board_id','trello_list_id','name','web_hook_id','web_hook_enable'];
}
