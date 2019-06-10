<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardBugs extends Model
{
    protected $fillable = ['board_id', 'card_id', 'type'];
}
