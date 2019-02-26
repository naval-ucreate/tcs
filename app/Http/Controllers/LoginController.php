<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
class LoginController extends Controller
{
    public function trelloLogin()
    {
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
        dd(request());

    }

}
