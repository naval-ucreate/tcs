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

    public function getDataFromApi(Array $user_boards_data){         
            $trello_board_ids   =   array_column($user_boards_data,'trello_board_id');
            $trello_boards      =   app('trello')->getUserBoards();          
            $add_new_board      =   []; 
            $del_old_board      =   []; 
            foreach($trello_boards as $trello_boards_val){
                  if(!in_array($boardVal['id'],$trello_board_ids)){
                    $add_new_board[] = [
                        'trello_user_id'=>$user_info['trello_id'],
                        'user_id'=>$user_info['id'],
                        'name'=> $trello_boards_val['name'],
                        'image'=> $trello_boards_val['prefs']['backgroundImage'],
                        'trello_board_id'=> $trello_boards_val['id'],
                        'background_image' => $trello_boards_val['prefs']['backgroundImage'],
                        'background_tile' => $trello_boards_val['prefs']['backgroundTile'],
                        'background_brightness' => $trello_boards_val['prefs']['backgroundBrightness'],
                        'background_bottom_color' => $trello_boards_val['prefs']['backgroundBottomColor'],
                        'background_top_color' => $trello_boards_val['prefs']['backgroundTopColor'],
                        'can_be_public' => $trello_boards_val['prefs']['canBePublic'],
                        'can_be_enterprise' => $trello_boards_val['prefs']['canBeEnterprise'],
                        'can_be_org' => $trello_boards_val['prefs']['canBeOrg'],
                        'can_be_private' => $trello_boards_val['prefs']['canBePrivate'],
                        'can_invite' => $trello_boards_val['prefs']['canInvite'],
                        'members'=> json_encode($trello_boards_val['memberships']),
                        'total_members' => count($trello_boards_val['memberships'])
                    ];  
               }
            }           
            if(count($add_new_board>0)){
                $this->store($add_new_board);
            }
          
            foreach($trello_board_ids as $trello_board_val){
                if(!in_array($trello_board_val,array_column($trello_boards,'id'))){
                    $del_old_board[]=$trello_board_val;
                }
            }
            if(count($del_old_board>0)){
                Board::whereIn('trello_board_id','=',$del_old_board)->delete();
            }            
            $user_boards_data    =   Board::where('user_id','=',$user_boards_data['id'])->get()->toArray();
            return $user_boards_data;  
    }
    public function showBoards(){
        $user_info      = Session::get('userinfo');
        $user_boards    = Board::where('user_id','=',$user_info['id'])->get();
        if(!count($user_boards->toArray())){
            if($user_info['total_board']>0){
                $trello_boards =    app('trello')->getUserBoards();
                if(count($trello_boards)){
                    foreach($trello_boards as $trello_boards_val):
                        $insert_data[] = [
                                        'trello_user_id'=>$user_info['trello_id'],
                                        'user_id'=>$user_info['id'],
                                        'name'=> $trello_boards_val['name'],
                                        'image'=> $trello_boards_val['prefs']['backgroundImage'],
                                        'trello_board_id'=> $trello_boards_val['id'],
                                        'background_image' => $trello_boards_val['prefs']['backgroundImage'],
                                        'background_tile' => $trello_boards_val['prefs']['backgroundTile'],
                                        'background_brightness' => $trello_boards_val['prefs']['backgroundBrightness'],
                                        'background_bottom_color' => $trello_boards_val['prefs']['backgroundBottomColor'],
                                        'background_top_color' => $trello_boards_val['prefs']['backgroundTopColor'],
                                        'can_be_public' => $trello_boards_val['prefs']['canBePublic'],
                                        'can_be_enterprise' => $trello_boards_val['prefs']['canBeEnterprise'],
                                        'can_be_org' => $trello_boards_val['prefs']['canBeOrg'],
                                        'can_be_private' => $trello_boards_val['prefs']['canBePrivate'],
                                        'can_invite' => $trello_boards_val['prefs']['canInvite'],
                                        'members'=> json_encode($trello_boards_val['memberships']),
                                        'total_members' => count($trello_boards_val['memberships'])
                                    ];                                               
                    endforeach;
                    $user_boards   =  $this->store($insert_data);
                    return view('dashboard/show-board',compact('user_boards'));
                }
            }
        } 
       
        if(time()>$user_info['last_api_hit']){
            $user_boards =   $this->getDataFromApi($user_boards->toArray());
        }
        return view('dashboard/show-board',compact('user_boards'));
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
