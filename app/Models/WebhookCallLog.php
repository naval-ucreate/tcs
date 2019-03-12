<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookCallLog extends Model
{

    protected $table = 'call_logs_hook';

    protected $fillable = [
        'body'
    ];
}
