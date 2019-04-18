<?php  

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
class TrelloApi {

    public $api_key='';
    public $client;
    const ApiEndPoint='';
    public $token='';

    public function __construct(String $api_key){
        $this->api_key = $api_key;
        $this->client  =  new Client();
        if(Auth::user()){
            $this->token=Auth::user()->toArray()['token'];
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

    public function getUserBoards(Array $option=[]){
        $url  = config("app.trello_api_end_point").'members/me/boards?key='.$this->api_key.'&token='.$this->token;
        if(count($option)>0){
            foreach($option as $key=>$value){
                $url.='&'.$key.'='.$value;
            }
        }
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }

    public function GetBoardList(String $board_id,Array $option=[]){
        $url        = config("app.trello_api_end_point").'boards/'.$board_id.'?key='.$this->api_key.'&token='.$this->token;
        $url       .= "&fields=all&lists=all&list_fields=all";
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }

    public function getCardChecklists(string $card_id,string $token=''){
        if(strlen($token)==0){
            $token=$this->token;
        }
        $url = config("app.trello_api_end_point").'cards/'.$card_id.'/checklists?key='.$this->api_key.'&token='.$token;
        $url.="&checkItems=all";
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }

    public function getListPos(string $list_id,string $token=''){
        if(strlen($token)==0){
            $token=$this->token;
        }
        $url = config("app.trello_api_end_point").'lists/'.$card_id.'?key='.$this->api_key.'&token='.$token;
        $url.="&checkItems=all";
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }
}

 