<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TestingHook extends Model
{
    use Notifiable;

    protected $fillable = [
        'details',
    ];

   
}
