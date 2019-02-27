<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Session;
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

                Session::put('userinfo', $insert_data);
                
            }

        }
    }



    public function addSession(){
        $insert_data=[
            'name' => 'naval kishor',
            'email' => 'naval@gmail.com',
            'username' => 'naval66',
            'token' => 'naval66',
            'image' => 'xxx',
            'trello_id' => 'xxx',
            'trello_url' => 'xxx',
            'confirmed' => 'xxx',
            'memberType' => 'xxx',
           
        ];
        Session::put('userinfo', $insert_data);
    }

    public function logout(Request $request){
        $request->session()->forget('userinfo');
        return redirect()->route('login');
    }


}
