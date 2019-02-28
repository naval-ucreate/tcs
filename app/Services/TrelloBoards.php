<?php 

namespace App\Services;

use App\Service\TrelloApi;

class TrelloBoards extends  TrelloApi {


    protected $fields =[
        'name'
    ];

    private $trello_token=''; 
    
    public function __construct(String $api_key){
        $this->api_key = $api_key;
        $this->client  =  new Client();
    }



}

