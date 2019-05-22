<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardConfiguration extends Model
{
    protected $fillable = ['board_id', 'list_id', 'type', 'status'];

    public function board() {
        return $this->belongsTo('App\Models\Board','trello_board_id','board_id');
    }

}
