<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardActivity extends Model
{
    protected $fillable = ['board_id','user_id','card_id','to_list_id','from_list_id'];
}
