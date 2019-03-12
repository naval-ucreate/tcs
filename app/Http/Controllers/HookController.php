<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HookController extends Controller
{
    
    public function RegisterHook(string $list_id){
        $response=app('trello')->RegisterHookList($list_id);
        
    }



}
