<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Trello\Client;
use Trello\Service;
use Trello\Events;

class TrelloListController extends Controller
{
    //





    public function postGuzzleRequest(){

        $token                  = Session::get('userinfo')['token'];
        $api                    = '5b60d3f32d9fadef119dfaf96af008ba';
        $client                 = new \GuzzleHttp\Client();
        $headers                = ['Content-Type: application/json'];
        $url                    = "https://api.trello.com/1/tokens/".$token."/webhooks/";
        $myBody['key']          = $api;
        $myBody['callbackURL']  = "http://trellocontrollchecklist.herokuapp.com/test-web-hook";
        $myBody['idModel']      = "5c6bb49e2b175466e1f763a1";
        $myBody['description']  = "My First Trello App";
        $request                = $client->post($url,$headers,['form_params'=>$myBody]);
        $response               = $request->send();
        dd($response);
    }

    function toWriteInFile($board){
        $myfile = fopen( getcwd()."newfile.txt", "w") or die("Unable to open file!");
        $txt    = $board;
        fwrite($myfile, $txt);
        $txt    = "Jane Doe\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }

    public function testWebHook(){  
        
        $token  =  Session::get('userinfo')['token'];
        $api    = '5b60d3f32d9fadef119dfaf96af008ba';
        $client = new Client();
        $client->authenticate($api,$client, Client::AUTH_URL_CLIENT_ID);

        $service = new Service($client);



        dd("test");

        // Bind a callable to a given event...
        $service->addListener(Events::BOARD_UPDATE, function ($event) {
        $board = $event->getBoard();

        print_R($board);
        $this->toWriteInFile($board);   
        // do something
        });
    }

}
