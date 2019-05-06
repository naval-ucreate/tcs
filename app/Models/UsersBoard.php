<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersBoard extends Model
{

    protected $fillable = ['user_id', 'trello_board_id', 'is_admin'];

    public function boards(){
        return $this->belongsTo('App\Models\Board','trello_board_id','trello_board_id');
    }

}
