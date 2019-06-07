<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;
use App\Models\TestingHook;
use App\Repositories\Lists\ListRepository;
use App\Repositories\BoardConfigurations\BoardConfigurationsRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Boards\BoardRepository;

class ListController extends Controller
{
    
    private $list, $login_user, $board_config, $board;

    public function __construct(ListRepository $list, BoardConfigurationsRepository $board_config, BoardRepository $board){
        $this->list = $list;
        $this->board_config = $board_config;
        $this->board = $board;
    }

    public function store(array $data,$id){
        if($this->list->insertMany($data)) {
            return $this->list->findByTrelloBoardId($id);  
        }
    }

    public function trelloList(String $id){
        $this->login_user = Auth::user()->toArray();       
        $baord_info = $this->board->getBoardId($id);
        return view('dashboard/show-list', compact('baord_info'));         
    }

    public function listJson(String $id){
        $this->login_user = Auth::user()->toArray();       
        $baord_info = $this->board->getBoardId($id);
        $board_list = $this->list->findByTrelloBoardId($id);
        
        if(count($board_list)==0) {
            $list_data = app('trello')->GetBoardList($baord_info->trello_board_id);
            if(count($list_data)) {
                $insert_data = self::makeArrayList($list_data, $id);
                if(count($insert_data)) {
                    $board_list  = $this->store($insert_data,$id); 
                    return $board_list;   
                }
            } 
        }

        if( strtotime('+7 hour', time()) > $this->login_user['last_api_hit'] ) {
            $board_list = $this->checkNewList($baord_info->trello_board_id, $board_list, $id);
        }
        return $board_list;         
    }

    private function checkNewList(string $board_id, array $board_list, int $id){
        $api_data = app('trello')->GetBoardList($board_id);
        $api_data = self::makeArrayList($api_data, $id);
        $db_data = array_column($board_list, 'trello_list_id');
        if(count($api_data)) {
            $new_list_id = newArrayElement($db_data, $api_data);
            if(count($new_list_id['new_list'])) {
                return $this->store($new_list_id['new_list'], $id);
            }
        }
        if($this->deleteList($api_data, $db_data)) {
            $board_list = $this->list->findByTrelloBoardId($id);
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

    static private function makeArrayList(array $list_data, $id) {
        $insert_data = [];
        foreach($list_data['lists'] as $list_val) {
            $insert_data[] = [
                'board_id' => $id,
                'trello_list_id'=>$list_val['id'],
                'name'=> $list_val['name'],
                'position' => $list_val['pos'],
                'is_archived' => $list_val['closed'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        return $insert_data;
    }

    public function updateListcheckList($list_id) {
        $data = request()->toArray();
        $board_config = $this->board_config->boardConfigByTypeAndList($data['board_id'], $data['type'], $list_id);
        
        if(isset($board_config)) {
            $board_config->status = $data['status'];
            $board_config->list_id = $list_id;
            if($data['type'] == 1 && $data['status']) {
                $board_config->lable_name = $data['lable_name'];
                $board_config->lable_color = $data['lable_color'];
                $board_config->checklist_type = $data['checklist_type'];
            }
            $board_config->save();
            return $board_config->toArray();
        }
        $attribute = [
            'list_id' => $list_id,
            'board_id' => $data['board_id'],
            'type' => $data['type'],
            'status' => $data['status']
        ];
        if($data['type'] == 1) {
            $attribute['lable_name'] = $data['lable_name'];
            $attribute['lable_color'] = $data['lable_color'];
            $attribute['checklist_type'] = $data['checklist_type'];
        }
        if($this->board_config->create($attribute)) {
            return $this->board_config->boardConfigByTypeAndList($data['board_id'], $data['type'], $list_id)->toArray();
        }
        return 0;
    }


    public function enableBug(string $board_id){
       $data = request()->toArray();
       $list_id = $data['list_ids'];
       $list_id = explode(',', $list_id);
       $board_config = $this->board_config->boardConfigByTypeAll($board_id, $data['type']);
       if(!$board_config) {
            $attributes = self::boardListArray($list_id, $board_id, $data['type']);   
            $this->board_config->insert($attributes);
            return 1;
       }
       foreach($board_config as $value):
            if(in_array($value->list_id, $list_id)){
                $value->status = true;
                $value->save();
            }else{
                $value->status = false;
                $value->save();
            }         
       endforeach;
       $board_config = $board_config->toArray();
       $db_list = array_column($board_config, 'list_id');
       $new_list_ids = [];
       foreach($list_id as $value):
        if(!in_array($value, $db_list)){
            $new_list_ids[] = $value;
        }
       endforeach;
       if(count($new_list_ids)) {
            $attributes = self::boardListArray($new_list_ids, $board_id, $data['type']);   
            $this->board_config->insert($attributes);
       } 
       return 1;
    }

    static private function boardListArray(Array $list_ids, String $board_id, int $type) {
        $final = [];
        foreach($list_ids as $value):
            $final[] = [
                'list_id' => $value,
                'board_id' => $board_id,
                'type' => $type,
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        endforeach;
        return $final;    
    }

    public function testWebHook(){
        $data=json_decode(request()->getContent(), true);
        TestingHook::create([
            'details' => json_encode($data)
        ]);
        return 1;
    }

}
