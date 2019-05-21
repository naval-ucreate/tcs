<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    //
    protected $table = 'board_lists';

    protected $fillable = ['trello_board_id','trello_list_id','name','web_hook_id','web_hook_enable'];

    public function board()
    {
        return $this->belongsTo('App\Models\Board','trello_board_id','trello_board_id');
    }

    public function boardConfig() {
        return $this->belongsTo('App\Models\BoardConfiguration','trello_list_id','list_id');
    }

}
