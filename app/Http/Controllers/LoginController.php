<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // @ help https://github.com/ixudra/curl
    public function trelloLogin()
    {
        return view('login/login');
    }
    function toSendJsonRequest($url)
    {
        $client     = new \GuzzleHttp\Client();
        $response   = $client->request('GET',$url);
        if($response->getStatusCode()==200)
        {
            return json_decode($response->getBody(), true);
        }
        // return to login page with error
    }
    function toGetUserInfo($oAuthToken)
    {
        $apiUrl     =   env('TRELLO_API_URL');
        $endPoint   =   'members/me';    
        $parameter  =   http_build_query(['key'=>env('TRELLO_API_KEY'),'token'=>$oAuthToken]);       
        $finalUrl   =   $apiUrl."/".$endPoint."?".$parameter;
        $res        =   $this->toSendJsonRequest($finalUrl);
        return $res;  
    }   

    public function checkTrelloLogin(Request $request)
    {
        print_r($_POST);
        $userData   =   $this->toGetUserInfo($request->input('oAuthToken'));
        print_R($userData);
        //if token comes we send request to get userinfo
        //we add user info to user table 
        //we cross check user is already there or ? and his token updated or ?
        //we authenticate user and logged in him
        //After this we send request to all of his apis like bords cards lists checklists and save in database
        //we redirect the user to his dashboards where all of his boards display

    }
}
