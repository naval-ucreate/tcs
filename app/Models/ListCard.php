<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListCard extends Model
{
    protected $fillable = ['board_id', 'list_id','name', 'trello_card_id', 'description', 'total_bugs','is_complete'];
}
