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

    private $list, $card, $board_activity, $board_config, $board_member, $board;

    public function  __construct(BoardConfig $board_config, ListRepository $list, CardRepository $card, BoardActivity $board_activity, BoardMember $board_member, BoardRepository $board){
        $this->list = $list;
        $this->card = $card;
        $this->board_activity = $board_activity;
        $this->board_config = $board_config;
        $this->board_member = $board_member;
        $this->board = $board;
    }
    
    public function registerHook($board_id){
        $data = $this->board->getBoardId($board_id);
        if(!$data->web_hook_enable && $data->web_hook_id == '') {
            $hook_id = $this->saveHook($data->trello_board_id, $board_id);
            if($hook_id) {
                $user_info = Auth::user()->toArray();
                $data->web_hook_id = $hook_id;
                $data->web_hook_enable = true;
                $data->owner_token = $user_info['token'];
                $data->save();
                $this->addMembers($data->trello_board_id, $board_id);
                $this->updateBoardList($data->trello_board_id, $board_id);
                return 1;
            }
        }
        return 0;
    }

    public function removeHook($board_id){
        $data = $this->board->getBoardId($board_id);
        if($data->web_hook_enable && $data->web_hook_id != '') {
            if($this->deleteHook($data->web_hook_id)) {
                $data->web_hook_id = '';
                $data->web_hook_enable = false;
                $data->owner_token = '';
                $data->save();
                return 1;
            }
        }
        return 0;
    }


    public function UpdateHook(string $hook_id,string $list_id, string $board_id){
        if($response=app('trello')->UpdateHook($list_id, $hook_id)) {
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

    public function saveHook(string $trello_borad_id, int $board_id){
        $response = app('trello')->RegisterHookList($trello_borad_id, $board_id);
        $hook_data = json_validator($response);
        if(count($hook_data) > 0 &&  $hook_data) {
            return $hook_data['id'];
        }
        return 0;
    }
    
    public function ok($board_id){
        return 1;
    }

    public function deleteHook(string $board_id){
        return app('trello')->deleteHook($board_id) ? 1 : 0;
    }

    public function listenTrigger($board_id, WebhookCallLog $webhook_calllog){
        $data = json_decode(request()->getContent(), true);
        $data['board_int_id'] = $board_id;
        $webhook_calllog->create(['body' => json_encode($data)]);
        $trello_board_id = $data['model']['id'];
        if($data['action']['type'] == 'updateCard' && $data['action']['display']['translationKey'] == 'action_move_card_from_list_to_list'){
            $trello_entities = $data['action']['display']['entities'];
            $after_list_id = $trello_entities['listAfter']['id'];
            $befor_list_id = $trello_entities['listBefore']['id'];
            $db_lists_ids = $this->dbListId($after_list_id, $befor_list_id);
            $trello_card_id = $trello_entities['card']['id'];
            $card_information = $trello_entities['card'];
            $user_id = $trello_entities['memberCreator']['id'];
            $card_information['board_id'] = $board_id;
            $card_information['list_id'] = $db_lists_ids[0];
            $card_information['trello_before_id'] = $befor_list_id;
            $card_information['trello_after_id'] = $after_list_id;
            $db_card_id = $this->saveCard($card_information);
            $this->saveActivity($db_lists_ids[0], $db_lists_ids[1], $db_card_id, $board_id, $user_id); 
            $this->checkCheckList($db_lists_ids[0], $db_lists_ids[1], $db_card_id, $card_information);
        }
        if($data['action']['type'] == 'createList' && $data['action']['display']['translationKey'] == 'action_added_list_to_board') {
            $this->addNewList($board_id, $data['action']['display']['entities']['list']);
        }

        if($data['action']['type'] == 'updateList') {
            $this->updareList($data['action']['display']['entities']['list']);
        }

        return 0;
    }

    public function addMembers($trello_board_id, int $board_id) {
        $members = app('trello')->getBoardMembers($trello_board_id);
        $db_members = $this->board_member->findMembers($board_id);
        if(!isset($db_members)) {
            $attribute = self::makeMemberArray($members, $board_id);
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

    private function updateBoardList($trello_board_id, $board_id): void {
        $api_data = app('trello')->GetBoardList($trello_board_id);
        $db_data = $this->list->findByTrelloBoardId($board_id);
        $api_data = self::makeArrayList($api_data, $board_id);
        $trello_list_id = array_column($db_data, 'trello_list_id');
        if(count($api_data)) {
            $new_list_id = newArrayElement($trello_list_id, $api_data);
            if(count($new_list_id['new_list'])) {
                $this->list->insertMany($new_list_id['new_list']);
            }
            
            $new_list_id['old_list'] = addIdList($new_list_id['old_list'], $db_data);
            $ids = array_column($new_list_id['old_list'], 'id');
            $query = "update board_lists set name = case ";
            foreach($new_list_id['old_list'] as $key => $value) {
                $query.= " when id='".$value['id']."' then '".$value['name']."'";
            }
            $query.= ' end , position = case';
            foreach($new_list_id['old_list'] as $key => $value) {
                $query.= " when id='".$value['id']."' then ".$value['position']."";
            }
            // $query.= ' end , is_archived = case';
            // foreach($new_list_id['old_list'] as $key => $value) {
            //     $query.= " when id='".$value['id']."' then ".$value['closed']."";
            // }
            $query .= " end where id in ( ". Implode(',', $ids ). " ) ";
            $this->list->updateMany($query);   
        }
    }
    
   private function addNewList(int $board_id, Array $listInfo, WebhookCallLog $webhook_calllog){
        $attribute = [
            'board_id' => $board_id,
            'trello_list_id' => $listInfo['id'],
            'name' => $listInfo['text'],
            'is_archived' => false
        ];
        $webhook_calllog->create(['body' => json_encode($attribute)]);
        $this->list->create($attribute);
        return 1;
   }
   
     private function dbListId(string $after_list_id, string $befor_list_id){
        $data = $this->list->getMultipuleList([$after_list_id, $befor_list_id]);
        $after = ($data[0]->trello_list_id == $after_list_id)? $data[0]->id: $data[1]->id ;       
        $before = ($data[0]->trello_list_id == $befor_list_id)? $data[0]->id : $data[1]->id ; 
        return [
            $after,
            $before
        ];     
   }


    private function checkCheckList(int $after_list_id, int $befor_list_id,  int $card_id, Array $card_information):void {
        $list_info = $this->board_config->getConfigByListId($after_list_id, 1);
        if(isset($list_info)  && $list_info->status ) {
            $this->addLable($card_information['id'], 
            $list_info->board->owner_token,  
            $card_information['trello_before_id'], 
            $list_info->lable_name,
            $list_info->lable_color,
            $list_info->checklist_type
        );

        }
        $board_config = $this->board_config->boardConfigByTypeArray($card_information['board_id'], [2,3]);
        if($board_config){
            foreach($board_config as $value):
                if($value->list_id == $befor_list_id && $value->status ) {
                    $this->addRevertCount($card_id);
                    $this->addBugInCard($card_id, $list_info->board->owner_token, $card_information['id']);
                }
            endforeach;    
        }
        unset($board_config);
        unset($list_info);
    }

    private function updareList(Array $list):void {
        $db_list = $this->list->findByListId($list['id']);
        $db_list->name = $list['text'];
        
        if(isset($list['pos'])) {
            $db_list->position = $list['pos'];
        }
        if(isset($list['closed'])){
            $db_list->is_archived = $list['closed'];
        }
        $db_list->save();
    }

    private function saveActivity($befor_list_id, $after_list_id, $card_id, $borad_id, $user_id){
        $attribute = [
            'board_id' =>  $borad_id,
            'to_list_id' => $after_list_id,
            'from_list_id' => $befor_list_id,
            'card_id' => $card_id,
            'member_id' => $user_id
        ];
        $this->board_activity->create($attribute);
        return 1;
    }

    private function addBugInCard(int $card_id, string $token, string $trello_card_id){

        $response = app('trello')->getCardChecklists($trello_card_id, $token);
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
            $card_info = $this->card->findByCardIdNemuric($card_id);
            $card_info->total_bugs = $i;
            $card_info->save();
        }
        return 1;
    }

    private function addRevertCount(int $card_id):void {
        $card_info = $this->card->findByCardIdNemuric($card_id);
        $card_info->total_return = $card_info->total_return + 1;
        $card_info->save();
    }

    private function saveCard(array $card_information){
        
        $attribute = [
            'board_id' => $card_information['board_id'],
            'list_id' => $card_information['list_id'],
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
            return $this->card->findByCardId($card_information['id'])->id;
        }
        
        $this->card->create($attribute);
        return $this->card->findByCardId($card_information['id'])->id;
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

    private function addLable($card_id, $owner_token, $old_list_id, $message, $color, $type){
        $response = app('trello')->getCardChecklists($card_id, $owner_token);
        if(count($response) == 0) {
            app('trello')->moveCard($card_id, $old_list_id, $owner_token);
            app('trello')->addLable($card_id, $owner_token, $message, $color);
        } if($type == 1) {
            $checklist_array = array_column($response, 'checkItems');
            foreach($checklist_array as $k => $value) {
                foreach($value as $checklist) {
                    if($checklist['state'] == 'incomplete') {
                        app('trello')->moveCard($card_id, $old_list_id, $owner_token);
                        app('trello')->addLable($card_id, $owner_token, $message, $color);
                        break;
                    }
                }
            }
        }
        return 1;
    }

    static private function makeArrayList(array $list_data, $id){
        $insert_data = [];
        foreach($list_data['lists'] as $list_val) {
            $insert_data[] = [
                'board_id' => $id,
                'trello_list_id' => $list_val['id'],
                'name' => $list_val['name'],
                'position' => $list_val['pos'],
                'is_archived' => $list_val['closed'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        return $insert_data;
    }

    static private function makeMemberArray(Array $members, int $board_id){
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
