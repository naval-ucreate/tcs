<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListCard extends Model
{
    protected $fillable = ['trello_board_id','trello_list_id','name', 'trello_card_id', 'description', 'total_bugs','is_complete'];
}
