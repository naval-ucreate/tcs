<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;
use App\Http\Requests\RegisterHook;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Boards\BoardRepository;
use App\Repositories\Lists\ListRepository;
use App\Repositories\Cards\CardRepository;
use App\Repositories\BoardConfigurations\BoardConfigurationsRepository as BoardConfig;
use App\Repositories\BoardActivities\BoardActivitiesRepository as BoardActivity;
use App\Repositories\BoardMembers\BoardMemberRepository as BoardMember;
use App\Models\WebhookCallLog;

class HookController extends Controller
{

    private $list, $card, $board_activity, $board_config, $board_member;

    public function  __construct(BoardConfig $board_config, ListRepository $list, CardRepository $card, BoardActivity $board_activity, BoardMember $board_member){
        $this->list = $list;
        $this->card = $card;
        $this->board_activity = $board_activity;
        $this->board_config = $board_config;
        $this->board_member = $board_member;
    }
    
    public function registerHook($board_id, BoardRepository $board){
        $data = $board->getBoardId($board_id);
        if(!$data->web_hook_enable && $data->web_hook_id=='') {
            $hook_id = $this->saveHook($board_id);
            if($hook_id) {
                $user_info = Auth::user()->toArray();
                $data->web_hook_id = $hook_id;
                $data->web_hook_enable = true;
                $data->owner_token = $user_info['token'];
                $board->update($board_id, $data->toArray());
                $this->addMembers($board_id);
                $this->updateBoardList($board_id);
                return 1;
            }
        }
        return 0;
    }

    public function removeHook($board_id, BoardRepository $board){
        $data = $board->getBoardId($board_id);
        if($data->web_hook_enable && $data->web_hook_id!='') {
            if($this->deleteHook($data->web_hook_id)) {
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
        if($response=app('trello')->UpdateHook($list_id,$hook_id)) {
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
        if(count($hook_data) > 0 &&  $hook_data) {
            return $hook_data['id'];
        }
        return 0;
    }
    
    public function ok(){
        return 1;
    }

    public function deleteHook(string $board_id){
        if(app('trello')->deleteHook($board_id)) {
            return 1;
        }
        return 0;
    }

    public function listenTrigger(WebhookCallLog $webhook_calllog){
        $data = json_decode(request()->getContent(), true);
        $webhook_calllog->create(['body' => json_encode($data)]);
        $borad_id = $data['model']['id'];
        if($data['action']['type'] == 'updateCard' && $data['action']['display']['translationKey'] == 'action_move_card_from_list_to_list'){
            $after_list_id = $data['action']['display']['entities']['listAfter']['id'];
            $befor_list_id = $data['action']['display']['entities']['listBefore']['id'];
            $card_id = $data['action']['display']['entities']['card']['id'];
            $card_information = $data['action']['display']['entities']['card'];
            $user_id = $data['action']['display']['entities']['memberCreator']['id'];
            $card_information['board_id'] = $borad_id;
            $this->saveActivity($befor_list_id, $after_list_id, $card_id, $borad_id, $user_id); 
            $this->checkCheckList($after_list_id, $befor_list_id, $card_id, $card_information);
        }
        if($data['action']['type'] == 'createList' && $data['action']['display']['translationKey'] == 'action_added_list_to_board') {
            $this->addNewList($borad_id, $data['action']['display']['entities']['list']);
        }

        return 0;
    }

    public function addMembers($board_id) {
        $members = app('trello')->getBoardMembers($board_id);
        $db_members = $this->board_member->findMembers($board_id);
        if(!isset($db_members)) {
            $attribute = self::makeMemberArray($members, $board_id);
            dd($attribute);
            $this->board_member->insert($attribute);
            return 1;            
        }
        
        $db_members =  array_column($db_members->toArray(), 'user_id');
        $new_members = checkNewMember($db_members, $members);
        
        if(count($new_members) > 0) {
            $attribute = self::makeMemberArray($new_members, $board_id);
            $this->board_member->insert($attribute);
            return 1;
        }
        return 0;
    }

    private function updateBoardList($board_id): void {
        $api_data = app('trello')->GetBoardList($board_id);
        $db_data = $this->list->findByTrelloBoardId($board_id);
        $api_data = self::makeArrayList($api_data, $board_id);
        $db_data = array_column($db_data, 'trello_list_id');
        if(count($api_data)) {
            $new_list_id = newArrayElement($db_data, $api_data);
            if(count($new_list_id)) {
                $this->list->insertMany($new_list_id);
            }
        }
    }
    
   private function addNewList(string $board_id, Array $listInfo){
        $attribute = [
            'trello_board_id' => $board_id,
            'trello_list_id' => $listInfo['id'],
            'name' => $listInfo['text']
        ];
        $this->list->create($attribute);
        return 1;
   }      


    private function checkCheckList(String $after_list_id, string $befor_list_id,  String $card_id, Array $card_information){
        $list_info = $this->board_config->getConfigByListId($after_list_id, 1);
        $this->saveCard($card_information);
        if(isset($list_info)  && $list_info->status ) {
            $this->addLable($card_id, $list_info->board->owner_token,  $befor_list_id);
        }
        $board_config = $this->board_config->boardConfigByTypeArray($card_information['board_id'], [2,3]);
        if($board_config){
            foreach($board_config as $value):
                if($value->list_id == $befor_list_id && $value->status ) {
                    $this->addBugInCard($card_id, $list_info->board->owner_token);
                    $this->addRevertCount($card_id, $befor_list_id, $after_list_id);
                }
            endforeach;    
        }
        return 0;
    }

    private function saveActivity($befor_list_id, $after_list_id, $card_id, $borad_id, $user_id){
        $attribute = [
            'board_id' =>  $borad_id,
            'to_list_id' => $after_list_id,
            'from_list_id' => $befor_list_id,
            'card_id' => $card_id,
            'user_id' => $user_id
        ];
        $this->board_activity->create($attribute);
        return 1;
    }

    private function addBugInCard($card_id, $token){

        $response = app('trello')->getCardChecklists($card_id, $token);
        if(count($response)>0) {
            $checklist_array = array_column($response, 'checkItems');
            $i = 0;
            foreach($checklist_array as $k => $value) {
                foreach($value as $checklist){
                    if($checklist['state'] == 'incomplete') {
                        $i++;
                    }
                }
            }
            $card_info = $this->card->findByCardId($card_id);
            $card_info->total_bugs = $i;
            $card_info->save();
        }
        return 1;
    }

    private function addRevertCount($card_id, $after_list_id){
        $check_config = $this->board_config->getConfigByListId($after_list_id, 4);
        if($check_config && $check_config->status ) {
            $card_info = $this->card->findByCardId($card_id);
            $card_info->total_return = $card_info->total_return + 1;
            $card_info->save();
        }
        return 1;
    }

    private function saveCard(array $card_information){
        
        $attribute = [
            'trello_board_id' => $card_information['board_id'],
            'trello_list_id' => $card_information['idList'],
            'trello_card_id' => $card_information['id'],
            'name' => $card_information['text'],
            'description' => 'not found',
            'total_bugs' => 0,
            'is_complete' => true,
            'total_return' => 0,
        ];
        
        if($this->card->findByCardId($card_information['id'])) {
            unset( $attribute['is_complete']);
            unset( $attribute['total_bugs']);
            unset( $attribute['total_return']);
            $this->card->updateByCard($card_information['id'], $attribute);
            return 1;
        }
        
        $this->card->create($attribute);
        return 1;
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
                    if($checklist['state'] == 'incomplete') {
                        app('trello')->moveCard($card_id, $old_list_id, $owner_token);
                        app('trello')->addLable($card_id,$owner_token,'Checklist incomplete');
                        break;
                    }
                }
            }
            return 1;
        }
        app('trello')->moveCard($card_id, $old_list_id, $owner_token);
        app('trello')->addLable($card_id,$owner_token,'Checklist missing');
        return 1; 
    }

    static private function makeArrayList(array $list_data, $id){
        $insert_data = [];
        foreach($list_data['lists'] as $list_val) {
            $insert_data[] = [
                'trello_board_id' => $id,
                'trello_list_id' => $list_val['id'],
                'name' => $list_val['name'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        return $insert_data;
    }

    static private function makeMemberArray(Array $members, String $board_id){
        $final = [];
        foreach($members as $value):
            $final[] = [
                'user_id' => $value['idMember'],
                'board_id' => $board_id,
                'name' =>  $value['member']['fullName'],
                'username' => $value['member']['username'],
                'image' => $value['member']['avatarUrl'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        endforeach;
        return $final;    
    }
}
