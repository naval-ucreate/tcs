<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WebhookCallLog;
use Illuminate\Support\Facades\Session;

class TrelloListController extends Controller
{
   
    public function postGuzzleRequest(){

        $token                  = Session::get('userinfo')['token'];
        $api                    = '5b60d3f32d9fadef119dfaf96af008ba';
        $client                 = new \GuzzleHttp\Client();
        $headers                = ['Content-Type: application/json'];
        $url                    = "https://api.trello.com/1/tokens/".$token."/webhooks/";
        $myBody['key']          = $api;
        $myBody['callbackURL']  = "http://trellocontrollchecklist.herokuapp.com/api/test-web-hook";
        $myBody['idModel']      = "5c6bb49e2b175466e1f763a1";
        $myBody['description']  = "My First Trello App";
        $myBody['active']       = "true";
        $request                = $client->post($url,['form_params'=>$myBody]);
        $response               = $request->send();
       // dd($response);
    }
    public function testWebHook(WebhookCallLog $webhook_calllog)
    {      
      // $_POST = array("name"=>"naval");
        $data['body'] =  file_get_contents('php://input');
        $webhook_calllog->create($data);
    }   
    public function test(){
      return 1;
    }
   
}
