<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;
use App\Models\TestingHook;
use App\Repositories\Lists\ListRepository;
use App\Repositories\BoardConfigurations\BoardConfigurationsRepository;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    
    private $list, $login_user, $board_config;

    public function __construct(ListRepository $list, BoardConfigurationsRepository $board_config){
        $this->list = $list;
        $this->board_config = $board_config;
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

        if( strtotime('+7 hour', time()) > $this->login_user['last_api_hit'] ) {
            $board_list = $this->checkNewList($id, $board_list);
        }
        return view('dashboard/show-list',compact('board_list'));         
    }


    private function checkNewList(string $board_id, array $board_list){
        $api_data = app('trello')->GetBoardList($board_id);
        $api_data = self::makeArrayList($api_data, $board_id);
        $db_data = array_column($board_list, 'trello_list_id');
        if(count($api_data)) {
            $new_list_id = newArrayElement($db_data, $api_data);
            if(count($new_list_id['new_list'])) {
                return $this->store($new_list_id['new_list'], $board_id);
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

    static private function makeArrayList(array $list_data, $id){
        $insert_data = [];
        foreach($list_data['lists'] as $list_val) {
            $insert_data[] = [
            'trello_board_id'=>$id,
            'trello_list_id'=>$list_val['id'],
            'name'=> $list_val['name']
            ];
        }
        return $insert_data;
    }

    public function updateListcheckList($list_id){
        $data = request()->toArray();
        $board_config = $this->board_config->boardConfigByType($data['board_id'], $data['type']);
        
        if(isset($board_config)) {
            $board_config->status = $data['status'];
            $board_config->list_id = $list_id;
            $board_config->save();
            return 1;
        }

        $attribute = [
            'list_id' => $list_id,
            'board_id' => $data['board_id'],
            'type' => $data['type'],
            'status' => $data['status']
        ];

        if($this->board_config->create($attribute)) {
            return 1;
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
