<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    //
    protected $table = 'board_lists';

    protected $fillable = ['trello_board_id','trello_list_id','name'];

    public function board()
    {
        return $this->belongsTo('App\Models\Board', 'board_id', 'id');
    }

    public function boardConfig() {
        return $this->hasMany('App\Models\BoardConfiguration', 'list_id', 'id');
    }

}
