<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = [
        'trello_user_id','user_id','name','image','trello_board_id','members','total_board','last_api_hit'
    ];
}
