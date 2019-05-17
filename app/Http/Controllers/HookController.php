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
use App\Models\WebhookCallLog;

class HookController extends Controller
{

    private $list, $card;

    public function  __construct(ListRepository $list, CardRepository $card){
        $this->list = $list;
        $this->card = $card;
    }
    
    public function registerHook($board_id,BoardRepository $board){
        $data = $board->getBoardId($board_id);
        if(!$data->web_hook_enable && $data->web_hook_id=='') {
            $hook_id = $this->saveHook($board_id);
            if($hook_id) {
                $user_info = Auth::user()->toArray();
                $data->web_hook_id = $hook_id;
                $data->web_hook_enable = true;
                $data->owner_token = $user_info['token'];
                $board->update($board_id, $data->toArray());
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
        $after_list_id = $data['action']['display']['entities']['listAfter']['id'];
        $befor_list_id = $data['action']['display']['entities']['listBefore']['id'];
        $borad_id = $data['action']['id'];
        $card_id = $data['action']['display']['entities']['card']['id'];
        $card_information = $data['action']['display']['entities']['card'];
        $card_information['board_id'] = $borad_id;
        if($data['action']['type']=='updateCard' && $data['action']['display']['translationKey'] == 'action_move_card_from_list_to_list'){
            $this->checkCheckList($after_list_id, $befor_list_id, $card_id, $card_information); 
        }
        return 0;
    }
    
    
    private function checkCheckList(String $after_list_id, string $befor_list_id,  String $card_id, Array $card_information){
        $list_info = $this->list->findByListId($after_list_id);
        
        $this->saveCard($card_information);

        if($list_info->checklist_enable){
            $this->addLable($card_id, $list_info->board->owner_token,  $befor_list_id);
        }
        
        $list_info = $this->list->findByListId($befor_list_id);
        
        if($list_info->bug_enable){
            $this->addBugInCard($card_id, $list_info->board->owner_token);
            $this->addRevertCount($card_id, $befor_list_id, $after_list_id);
        }
        return 0;
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
            $card_info->total_bugs = $card_info->total_bugs + $i;
            $card_info->save();
        }
        return 1;
    }

    private function addRevertCount($card_id, $befor_list_id, $after_list_id){

        $after = $this->list->findByListId($after_list_id);
        $befor = $this->list->findByListId($befor_list_id);
        if($after->id > $befor->id) {
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
}
