<?php  

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class TrelloApi {

    private $api_key='';
    private $client;
    const ApiEndPoint='';
    private $token='';

    public function __construct(String $api_key){
        $this->api_key = $api_key;
        $this->client  =  new Client();
        if(Session::get('userinfo')){
            $this->token=Session::get('userinfo')['token'];
        }
    }



    public function getUserInfo(String $token){
        $url        = config("app.trello_api_end_point").'members/me?key='.$this->api_key.'&token='.$token;
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }


    public function getUserBoards(){
        $url        = config("app.trello_api_end_point").'members/me/boards?key='.$this->api_key.'&token='.$this->token;
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }

}
