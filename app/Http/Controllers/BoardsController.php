<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Board;
use Trello\Client;
use App\Models\User;
use App\Models\UsersBoard;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserBoards\UserBoardRepository;
use App\Repositories\Boards\BoardRepository;


class BoardsController extends Controller
{
    
    private $user_board_repository, $board, $login_user;


    public function __construct(UserBoardRepository $user_board_repository, BoardRepository  $board){
        $this->board = $board;
        $this->user_board_repository = $user_board_repository;
       
    }

    public function checkBoards(Array $user_boards_data){         
            $trello_board_ids = array_column($user_boards_data,'board_id');
            $trello_boards = app('trello')->getUserBoards();

            if(count($trello_boards)>0) {
                $insert_data = $this->insertArray($trello_boards);
                $user_boards = $this->store($insert_data);
            }
            $del_old_board = [];
            foreach($trello_board_ids as $trello_board_val){
                if(!in_array($trello_board_val,array_column($trello_boards,'id'))){
                    $del_old_board[]=$trello_board_val;
                }
            }

            if(count($del_old_board)>0){
               $this->board->deleteMany($del_old_board);
               $this->user_board_repository->deleteMany($del_old_board);
            }    
            $this->login_user['last_api_hit'] = strtotime("+24 hour",time()); 
            $this->login_user['total_board'] = count($trello_boards);

            User::where('id','=',$this->login_user['id'])->update($this->login_user); 
            
            return  $this->user_board_repository
            ->getUserBoards($this->login_user['trello_id']); 
    }

    public function showBoards(UsersBoard $userboard){
        $this->login_user = Auth::user()->toArray();
        $user_boards = $this->user_board_repository
        ->getUserBoards($this->login_user['trello_id']); 
        if(!is_null( $user_boards ) && count($user_boards)==0) {
            $trello_boards = app('trello')->getUserBoards();
            if(count($trello_boards)) {
                $insert_data = $this->insertArray($trello_boards);
                $user_boards = $this->store($insert_data);
                return view('dashboard/show-board',compact('user_boards'));
            }
        } 
        if(time()>$this->login_user['last_api_hit']) {
            $user_boards = $this->checkBoards($user_boards->toArray());
        }
        return view('dashboard/show-board',compact('user_boards'));
    }

    public function store(Array $data){
        if($this->board->insert($data)) {
            
            $this->user_board_repository
            ->insertUserBoard($this->userBoardArray($data));

            return $this->user_board_repository
            ->getUserBoards($this->login_user['trello_id']); 
        }
        return false;
    }

    public function distory(Board $board){
        $board->delete();
        return 1;
    }

    private function userBoardArray(array $data){
        $final = [];
        foreach($data as $key => $value):
            $final[]=[
                'user_id' => $this->login_user['trello_id'],
                'trello_board_id' => $value['trello_board_id'],
                'board_id' => $value['trello_board_id'],
                'is_admin' => array_exists($value['members'], $this->login_user['trello_id'])
                ?true:false
            ];
        endforeach;
        return $final;     
    }

    private function insertArray($trello_boards){
        $insert_data = [];
        foreach($trello_boards as $trello_boards_val):
            $insert_data[] = [
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
        return  $insert_data;   
    }
}