<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardConfiguration extends Model
{
    protected $fillable = ['board_id', 'list_id', 'type', 'status', 'lable_name', 'lable_color', 'checklist_type'];

    public function board() {
        return $this->belongsTo('App\Models\Board', 'board_id', 'id');    
    }

    public function list() {
        return $this->belongsTo('App\Models\BoardList', 'list_id', 'id');
    }

}
