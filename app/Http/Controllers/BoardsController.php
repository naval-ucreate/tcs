<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use Illuminate\Support\Facades\Session;
use Trello\Client;
use GuzzleHttp\Client as HttpClient;
class BoardsController extends Controller
{
 
    public function showBoards1(){
        return view('dashboard/show-board');
    }
    public function getDataFromApi(Array $userBoardsData){         
            //$userInfo       =   Session::get('userinfo');
            //$db_board        =   Board::where('user_id','=',$userInfo['id'])->get()->toArray();
            $dbBoardIds     =   array_column($userBoardsData,'trello_board_id');
            $boardsData     =   app('trello')->getUserBoards();          
            $add_new_board  =   []; 
            $del_old_board  =   []; 
            foreach($boardsData as $boardVal){
                  if(!in_array($boardVal['id'],$dbBoardIds)){
                    $add_new_board[]=[
                        'trello_user_id'=>$userInfo['trello_id'],
                        'user_id'=>$userInfo['id'],
                        'name'=> $boardVal['name'],
                        'image'=> $boardVal['prefs']['backgroundImage'],
                        'trello_board_id'=> $boardVal['id'],
                        'backgroundImage' => $boardVal['prefs']['backgroundImage'],
                        'backgroundTile' => $boardVal['prefs']['backgroundTile'],
                        'backgroundBrightness' => $boardVal['prefs']['backgroundBrightness'],
                        'backgroundBottomColor' => $boardVal['prefs']['backgroundBottomColor'],
                        'backgroundTopColor' => $boardVal['prefs']['backgroundTopColor'],
                        'canBePublic' => $boardVal['prefs']['canBePublic'],
                        'canBeEnterprise' => $boardVal['prefs']['canBeEnterprise'],
                        'canBeOrg' => $boardVal['prefs']['canBeOrg'],
                        'canBePrivate' => $boardVal['prefs']['canBePrivate'],
                        'canInvite' => $boardVal['prefs']['canInvite'],
                        'members'=> json_encode($boardVal['memberships']),
                        'total_members' => count($boardVal['memberships'])
                    ];      
               }
            }           
            if(count($add_new_board>0)){
                $this->store($insertData);
            }
          
            foreach($dbBoardIds as $dbBoardVal){
                if(!in_array($dbBoardVal,array_column($boardsData,'id'))){
                    $del_old_board[]=$dbBoardVal;
                }
            }
            if(count($del_old_board>0)){
                Board::whereIn('trello_board_id','=',$del_old_board)->delete();
            }
            
            $dbBoardData    =   Board::where('user_id','=',$userInfo['id'])->get()->toArray();
            return $dbBoardData;  
    }
    public function showBoards(){
        $userInfo    =    Session::get('userinfo');
        $oAuthToken  =    $userInfo['token'];
        $userBoardsData = Board::where('user_id','=',$userInfo['id'])->get();
        if(!count($userBoardsData->toArray())){
            if($userInfo['total_board']>0){
                $boardsData  =    app('trello')->getUserBoards();
                if(count($boardsData)){
                    foreach($boardsData as $boardVal):
                        $insertData[] = [
                                        'trello_user_id'=>$userInfo['trello_id'],
                                        'user_id'=>$userInfo['id'],
                                        'name'=> $boardVal['name'],
                                        'image'=> $boardVal['prefs']['backgroundImage'],
                                        'trello_board_id'=> $boardVal['id'],
                                        'backgroundImage' => $boardVal['prefs']['backgroundImage'],
                                        'backgroundTile' => $boardVal['prefs']['backgroundTile'],
                                        'backgroundBrightness' => $boardVal['prefs']['backgroundBrightness'],
                                        'backgroundBottomColor' => $boardVal['prefs']['backgroundBottomColor'],
                                        'backgroundTopColor' => $boardVal['prefs']['backgroundTopColor'],
                                        'canBePublic' => $boardVal['prefs']['canBePublic'],
                                        'canBeEnterprise' => $boardVal['prefs']['canBeEnterprise'],
                                        'canBeOrg' => $boardVal['prefs']['canBeOrg'],
                                        'canBePrivate' => $boardVal['prefs']['canBePrivate'],
                                        'canInvite' => $boardVal['prefs']['canInvite'],
                                        'members'=> json_encode($boardVal['memberships']),
                                        'total_members' => count($boardVal['memberships'])
                                    ];                                               
                    endforeach;
                    $userBoardsData   =  $this->store($insertData);
                    return view('dashboard/show-board',compact('userBoardsData'));
                }
            }
        } 
       
        if(time()>$userInfo['last_api_hit']){
            $userBoardsData=$this->getDataFromApi($userBoardsData->toArray());
        }
        return view('dashboard/show-board',compact('userBoardsData'));
    }

    public function store(Array $data){
        if(Board::insert($data)){
            $userInfo    =    Session::get('userinfo');
            return Board::where('user_id','=',$userInfo['id'])->get();  
        }
    }


    function addUpdateBoard($insertData){
        $authUserCount  =   Board::where('user_id',$insertData['user_id'])
                                ->where('trello_board_id',$insertData['trello_board_id'])
                                ->count(); 
        if($authUserCount>0){
            Board::where('trello_board_id',$insertData['trello_board_id'])->update($insertData);
            return true;
        }
        Board::create($insertData);        
        return true;
    }

    public function distory(Board $board){
        $board->delete();
        return 1;
    }

    public function updateBoard(){
        $boardId    =   '5c6bb49e2b175466e1f763a1';
        $name       =   'test11';
        $client     =   new Client();
        $userInfo   =   Session::get('userinfo');
        $oAuthToken =   $userInfo['token'];
        $client->authenticate(config('app.trello_key'), $oAuthToken, Client::AUTH_URL_CLIENT_ID);
        $client->boards()->setName($boardId, $name);
        dd($client);
    }

    public function TrelloList(String $id){
        $board=Board::where('trello_board_id','=',$id)->first();
        return view('dashboard/trelloList',compact('board'));
        
    }




}
