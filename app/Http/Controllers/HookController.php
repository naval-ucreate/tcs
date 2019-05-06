<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;
use App\Http\Requests\RegisterHook;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Boards\BoardRepository;
use App\Repositories\Lists\ListRepository;

class HookController extends Controller
{
    
    public function registerHook($board_id,BoardRepository $board){
        $data = $board->getBoardId($board_id);
        if(!$data->web_hook_enable && $data->web_hook_id==''){
            $hook_id = $this->saveHook($board_id);
            if($hook_id){
                $user_info = Auth::user()->toArray();
                $data->web_hook_id = $hook_id;
                $data->web_hook_enable = true;
                $data->owner_token = $user_info['trello_id'];
                $board->update($board_id, $data->toArray());
                return 1;
            }
        }
        return 0;
    }

    public function removeHook($board_id, BoardRepository $board){
        $data = $board->getBoardId($board_id);
        if($data->web_hook_enable && $data->web_hook_id!=''){
            if($this->deleteHook($data->web_hook_id)){
                $data->web_hook_id = '';
                $data->web_hook_enable = false;
                $data->owner_token = '';
                $board->update($board_id, $data->toArray());
                return 1;
            }
        }
        return 0;
    }


    public function UpdateHook(string $hook_id,string $list_id,string $board_id){
        if($response=app('trello')->UpdateHook($list_id,$hook_id)){
            if(BoardList::where([
                'trello_list_id' => $list_id,
                'trello_board_id' => $board_id   
            ])->update([
                'web_hook_enable' => true,
                'web_hook_id' =>   $hook_id
            ])){
                return 1;
            }
            return 0;
        }
        return 0;
    }

    public function saveHook(string $borad_id){
        $response = app('trello')->RegisterHookList($borad_id);
        $hook_data = json_validator($response);
        if(count($hook_data) > 0 &&  $hook_data){
            return $hook_data['id'];
        }
        return 0;
    }
    
    public function ok(){
        return 1;
    }

    public function deleteHook(string $board_id){
        if(app('trello')->deleteHook($board_id)){
            return 1;
        }
        return 0;
    }

    public function listenTrigger(){
        $data=json_decode(request()->getContent(), true);
        $after_list_id = $data['action']['display']['entities']['listAfter']['id'];
        $befor_list_id = $data['action']['display']['entities']['listBefore']['id'];
        $borad_id = $data['action']['id'];
        $card_id = $data['action']['display']['entities']['card']['id'];
        if($data['action']['type']=='updateCard' && $data['action']['display']['translationKey'] == 'action_move_card_from_list_to_list'){
            $this->checkCheckList($after_list_id, $befor_list_id, $card_id); 
        }
        return 0;
    }
    
    
    private function  checkCheckList(String $after_list_id, string $befor_list_id,  String $card_id, ListRepository $list){
        $list_info = $list->findByListId($after_list_id);
        if($list_info->web_hook_enable){
            $this->addLable($card_id, $list_info->board->owner_token,  $befor_list_id);
        }
        return 0;
    }

    public function after(){
        $data=json_decode(request()->getContent(), true);
        if($data['model']['id']!=$data['action']['data']['old']['idList']){
            if(array_key_exists('action',$data)) {
                if(array_key_exists('data',$data['action'])) {
                    if(array_key_exists('card',$data['action']['data'])) {
                        $card_id = $data['action']['data']['card']['id'];
                        $old_list_id = $data['action']['data']['old']['idList'];
                        $owner_token=Board::where([
                            ['trello_board_id' ,'=', $data['model']['idBoard']],
                            ['owner_token', '!=' ,'']
                        ])->first();
                        if($owner_token) {
                           $this->addLable($card_id, $owner_token, $old_list_id);
                        }
                    }
                }
            }
        }
        return 0;
    }

    private function addLable($card_id, $owner_token, $old_list_id){
        $response=app('trello')->getCardChecklists($card_id,$owner_token);
        if(count($response)>0) {
            $checklist_array = array_column($response, 'checkItems');
            foreach($checklist_array as $k => $value) {
                foreach($value as $checklist){
                    if($checklist['state']=='incomplete') {
                        app('trello')->addLable($card_id,$owner_token->owner_token,'Checklist incomplete');
                        app('trello')->moveCard($card_id, $old_list_id, $owner_token->owner_token);
                        break;
                    }
                }
            }
            return 1;
        }
        app('trello')->addLable($card_id,$owner_token->owner_token,'Checklist missing');
        app('trello')->moveCard($card_id, $old_list_id, $owner_token->owner_token); 
        return 1; 
    }
}
