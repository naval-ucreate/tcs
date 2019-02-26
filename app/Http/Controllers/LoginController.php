<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\User
class LoginController extends Controller
{
    public function trelloLogin(){
        return view('login/login');
    }
    function toSendJsonRequest($url)
    {
        $client     = new Client();
        $response   = $client->request('GET',$url);
        if($response->getStatusCode()==200)
        {
            return json_decode($response->getBody(), true);
        }
        // return to login page with error
    }



    public function ajax_login(){
        $requestdata = request()->validate(
            [
               'token' => 'required' 
            ]
        );

        $authUser=User::where('token',$requestdata['trello_token'])->first()->toArray();
        if(count($authUser){

        }
        $this->getUserInfo($requestdata['trello_token']);

    }

    public function getUserInfo(String $token){
        $client     = new Client();
        $url = config("app.trello_api_end_point").'members/me?key='.config(app.trello_key).'&token='.$token
        $response   = $client->request('GET',$url);
        if($response->getStatusCode()==200){
            $return_data=json_decode($response->getBody(), true);
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
