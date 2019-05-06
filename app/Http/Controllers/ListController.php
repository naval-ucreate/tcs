<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;
use App\Models\TestingHook;
use App\Repositories\Lists\ListRepository;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    
    private $list, $login_user;

    public function __construct(ListRepository $list){
        $this->list = $list;
    }

    public function store(array $data,$id){
        if($this->list->insertMany($data)) {
            return $this->list->findByTrelloBoardId($id);  
        }
    }

    public function trelloList(String $id){
        $this->login_user = Auth::user()->toArray();       
        $board_list = $this->list->findByTrelloBoardId($id);
        if(count($board_list)==0) {
            $list_data = app('trello')->GetBoardList($id);
            if(count($list_data)) {
                $insert_data = self::makeArrayList($list_data, $id);
                if(count($insert_data)) {
                    $board_list  = $this->store($insert_data,$id); 
                    return view('dashboard/show-list',compact('board_list'));
                }
            } 
        }
        if(time()>$this->login_user['last_api_hit']) {
            $board_list = $this->checkNewList($id,$board_list);
        }
        
        return view('dashboard/show-list',compact('board_list'));         
    }


    private function checkNewList(string $board_id, array $board_list){
        $api_data = app('trello')->GetBoardList($board_id);
        $api_data = self::makeArrayList($api_data, $board_id);
        $db_data = array_column($board_list, 'trello_list_id');
        if(count($api_data)) {
            $new_list_id = newArrayElement($db_data, $api_data);
            if(count($new_list_id)) {
                return $this->store($new_list_id, $board_id);
            }
        }
        if($this->deleteList($api_data, $db_data)) {
            $board_list = $this->list->findByTrelloBoardId($board_id);
        }
        return $board_list;
    }

    private function deleteList($api_data, $db_data){
        $api_data = array_column($api_data, 'trello_list_id');
        $delete_array =[];
        foreach($db_data as $value){
            if(!in_array($value,  $api_data)) {
                $delete_array[] = $value;
            }
        }
        if(count($delete_array)>0) {
            $this->list->deleteMany($delete_array);
            return true;
        }
        return false;
    }

    private static function makeArrayList(array $list_data, $id){
        $insert_data = [];
        foreach($list_data['lists'] as $list_val) {
            $insert_data[] = [
            'trello_board_id'=>$id,
            'trello_list_id'=>$list_val['id'],
            'name'=> $list_val['name'],
            'web_hook_enable'=>0,
            ];
        }
        return $insert_data;
    }

    public function updateListcheckList($list_id){
        $data = request()->toArray();
        if(isset($data['board_id'])){
            $this->list->disbaleAll($data['board_id']);
        }
        $update = ['web_hook_enable' => $data['status']];
        if($this->list->update($list_id, $update)){
            return 1;
        }
        return false;
    }

    public function testWebHook(){
        $data=json_decode(request()->getContent(), true);
        TestingHook::create([
            'details' => json_encode($data)
        ]);
        return 1;
    }

}
