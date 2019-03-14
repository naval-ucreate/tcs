<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Session;
class LoginController extends Controller
{
    public function trelloLogin(Request $request){
        if($request->session()->exists('userinfo')) {
            return redirect()->route('main-dashboard');
        }
        return view('login/login');
    }

    public function ajax_login(){
        $requestdata = request()->validate(
            [
               'trello_token' => 'required' 
            ]
        );
        $authUser=User::where('token',$requestdata['trello_token'])->first();
        if($authUser){
            Session::put('userinfo', $authUser->toArray());
            return [
                'success' => true,
                'message' => 'login successfully'
            ];
        }
        if($this->store($requestdata['trello_token'])){
            return [
                'success' => true,
                'message' => 'login successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Error to login'
        ];

    }

    public function store(String $token){
        $return_data=app('trello')->getUserInfo($token);
        if(count($return_data)){
            $insert_data=[
                'name' => $return_data['fullName'],
                'username' => $return_data['username'],
                'image' => $return_data['avatarUrl'],
                'trello_id' => $return_data['id'],
                'trello_url' => $return_data['url'],
                'confirmed' => $return_data['confirmed'],
                'memberType' => $return_data['memberType'],
                'email' => $return_data['email'],
                'token' => $token,
                'total_board' => count($return_data['idBoards']),
                'last_api_hit' => strtotime('+24 hours',time())
            ];
            if($user=User::create($insert_data)){
                $insert_data['id']=$user->id;
                Session::put('userinfo', $insert_data);
                return true;
            }
            return false;
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
        
    }

    public function logout(Request $request){
        $request->session()->forget('userinfo');
        return redirect()->route('login');
    }


}
