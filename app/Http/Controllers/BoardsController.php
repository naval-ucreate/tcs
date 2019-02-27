<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use GuzzleHttp\Client as HttpClient;
class BoardsController extends Controller
{


    function toSendJsonRequest($url)
    {
        $client     = new HttpClient();
        $response   = $client->request('GET',$url);
        if($response->getStatusCode()==200)
        {
            return json_decode($response->getBody(), true);
        }
        // return to login page with error
    }
    function toGetUserBoards($oAuthToken)
    {
        $apiUrl     =   env('TRELLO_API_URL');
        $endPoint   =   'members/me/boards';    
        $parameter  =   http_build_query(['fields'=>'all','key'=>env('TRELLO_API_KEY'),'token'=>$oAuthToken]);       
        $finalUrl   =   $apiUrl."/".$endPoint."?".$parameter;
        $res        =   $this->toSendJsonRequest($finalUrl);
        return $res;  
    }   
    public function showBoards()
    {
        return view('dashboard/show-board');
    }
    public function saveBoards()
    {
       $oAuthToken  =   '6ed2a41e48d27d28586223c95939c872f3d0c5396f42a51dff04f373360c1cdc';
       $boardsData  =   $this->toGetUserBoards($oAuthToken);
       echo "<pre>";
       print_r($boardsData);
       echo "<pre>";
    }




}
