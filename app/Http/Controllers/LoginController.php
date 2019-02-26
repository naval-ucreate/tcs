<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp\Client as Httpclient;

class LoginController extends Controller
{
    
    function toSendJsonRequest($url)
    {
        $client     = new Httpclient();
        $response   = $client->request('GET',$url);
        if($response->getStatusCode()==200)
        {
            return json_decode($response->getBody(), true);
        }
        // return to login page with error
    }

    public function trelloLogin(){
        return view('login.login');
    }


    public function ajax_login(){
        dd(request());

    }

}
