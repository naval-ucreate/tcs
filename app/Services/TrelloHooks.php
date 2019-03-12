<?php 

namespace App\Services;

use GuzzleHttp\Client;
class TrelloHooks extends  TrelloApi {


    // todo 
    public function __construct(String $api_key){
        parent::__construct($api_key);
    }


    public function RegisterHook(String $list_id){
        $url        = config("app.trello_api_end_point").$this->token.'/webhooks/';
        $body=[];
        $body['key']          =  $this->api_key;
        $body['callbackURL']  = "http://trellocontrollchecklist.herokuapp.com/api/test-web-hook";
        $body['idModel']      = $list_id;
        $body['description']  = "Creating the hook of list";
        $body['active']       = "true";
        $response   = $this->client->post($url,['form_params'=>$body]);
        return $response;
        
    }


    public function UpdateHook(string $list_id,String $hook_id){

    }

    public function DeleteHook(string $hook_id){
        
    }


}
