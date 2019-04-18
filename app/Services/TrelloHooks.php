<?php 

namespace App\Services;

use GuzzleHttp\Client;
class TrelloHooks extends  TrelloApi {


    public function __construct(String $api_key){
        parent::__construct($api_key);
    }


    public function RegisterHookList(String $list_id){
        $url        = config("app.trello_api_end_point").'tokens/'.$this->token.'/webhooks/';
        $body=[];
        $body['key']          =  $this->api_key;
        $body['callbackURL']  = $_SERVER['HTTP_ORIGIN'].'/api/list-trigger';
        $body['idModel']      = $list_id;
        $body['description']  = "Creating the hook of list";
        $body['active']       = "true";
        $response   = $this->client->post($url,['form_params'=>$body]);
        if($response->getStatusCode()==200){
            return $response->getBody();
        }
        return false;
    }

    public function UpdateHook(string $list_id,String $hook_id){
        $url = config("app.trello_api_end_point").'webhooks/'.$hook_id;
        $url.= "?idModel=".$list_id.'&key='.$this->api_key.'&token='.$this->token;
        $response   = $this->client->put($url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        return false;
    }

    public function DeleteHook(string $hook_id){
        $url        = config("app.trello_api_end_point").'webhooks/'.$hook_id.'?key='.$this->api_key.'&token='.$this->token;
        $response   = $this->client->delete($url);
        if($response->getStatusCode()==200){
            return $response->getBody();
        }
        return false;
    }

    public function addLable(String $card_id,$token=''){
        if(strlen($token)==0){
            $token=$this->token;
        }
        $url = config("app.trello_api_end_point").'cards/'.$card_id.'/labels?key='.$this->api_key.'&token='.$token;
        $url.="&color=red&name=please complete checklist";
        $response   = $this->client->post($url);
        if($response->getStatusCode()==200){
            return $response->getBody();
        }
        return false;
    }

    public function moveCard(String $card_id, String $list_id, $token=''){
        if(strlen($token)==0){
            $token=$this->token;
        }
        $url = config("app.trello_api_end_point").'cards/'.$card_id.'?idList='.$list_id.'&key='.$this->api_key.'&token='.$token;
        $response   = $this->client->post($url);
        if($response->getStatusCode()==200){
            return $response->getBody();
        }
        return false;
    }
}
