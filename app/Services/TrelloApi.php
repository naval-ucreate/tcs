<?php  

namespace App\Services;
use GuzzleHttp\Client;

class TrelloApi {

    private $api_key='';


    public function __construct(String $api_key){
        $this->api_key=$api_key;
    }

    public function userInfo(String $token){
        $client     = new Client();
        $url        = config("app.trello_api_end_point").'members/me?key='.$this->api_key.'&token='.$token;
        $response   = $client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }

}

