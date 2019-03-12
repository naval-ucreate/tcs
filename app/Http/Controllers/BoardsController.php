<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Board;
use Illuminate\Support\Facades\Session;
use Trello\Client;
use App\Models\User;
use App\Models\BoardList;
use GuzzleHttp\Client as HttpClient;
class BoardsController extends Controller
{
 
    /**
     * 
     */
    public function checkBoards(Array $user_boards_data){         
            $user_info          =   Session::get('userinfo');
            $trello_board_ids   =   array_column($user_boards_data,'trello_board_id');
            $trello_boards      =   app('trello')->getUserBoards();          
            $add_new_board      =   []; 
            $del_old_board      =   []; 
            foreach($trello_boards as $trello_boards_val){
                  if(!in_array($trello_boards_val['id'],$trello_board_ids)){
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
            if(count($add_new_board)>0){
                $this->store($add_new_board);
            }
          
            foreach($trello_board_ids as $trello_board_val){
                if(!in_array($trello_board_val,array_column($trello_boards,'id'))){
                    $del_old_board[]=$trello_board_val;
                }
            }
            if(count($del_old_board)>0){
                Board::whereIn('trello_board_id','=',$del_old_board)->delete();
            }    
            
            $user_info['last_api_hit']=strtotime("+24 hour",time()); // for add the 24 hr in current time.
            $user_info['total_board']=count($trello_boards); // update the total boards in session 
            Session::put('userinfo', $user_info); // update userinfo current user info
            User::where('id','=',$user_info['id'])->update($user_info); // update user info in db.
            $user_boards_data    =   Board::where('user_id','=',$user_info['id'])->get()->toArray(); // get all update boards.
            return $user_boards_data;  // return array 
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
        /**
         * check new board after every 24 hr .
         * If any board delete and update on trello then update and delete in local db.
         * @param Array
         * @return Array
         */
        if(time()>$user_info['last_api_hit']){
            $user_boards =   $this->checkBoards($user_boards->toArray());
        }
        return view('dashboard/show-board',compact('user_boards'));
    }
    public function store(Array $data){
        if(Board::insert($data)){
            $userInfo    =    Session::get('userinfo');
            return Board::where('user_id','=',$userInfo['id'])->get();  
        }
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
        $list           =   BoardList::where('trello_board_id','=',$id)->get()->toArray;
        if(!count($list)){
            $list_data      =   app('trello')->GetBoardList($id);
            if(count($list_data))
            {
                foreach($list_data['lists'] as $list_val)
                {
                    $insert_data[] = [
                    ];
                   echo $list_val['name']; 
                }
                //dd($list_data);
                // return view('dashboard/trelloList',compact('list_data'));
            } 
        }         
    }
}