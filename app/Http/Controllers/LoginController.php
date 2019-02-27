<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class LoginController extends Controller
{
    public function trelloLogin(){
        return view('login/login');
    }

    public function ajax_login(){
        $requestdata = request()->validate(
            [
               'token' => 'required' 
            ]
        );

        $authUser=User::where('token',$requestdata['trello_token'])->first()->toArray();
        if(count($authUser)){

        }
        $this->store($requestdata['trello_token']);

    }

    public function store(String $token){
        $return_data=app('trello')->getUserInfo($token);
        if($return_data){
            $insert_data=[
                'name' => $return_data['fullName'],
                'username' => $return_data['username'],
                'image' => $return_data['avatarUrl'],
                'trello_id' => $return_data['id'],
                'trello_url' => $return_data['url'],
                'confirmed' => $return_data['confirmed'],
                'memberType' => $return_data['memberType'],
                'email' => $return_data['email']
            ];
            if(User::create($insert_data)){
                
            }

        }
    }


}
