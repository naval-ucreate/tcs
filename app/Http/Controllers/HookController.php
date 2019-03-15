<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;
use App\Http\Requests\RegisterHook;

class HookController extends Controller
{
    
    public function RegisterHook(RegisterHook $request,BoardList $list){
        $data=$list->where([
            'trello_board_id' => $request->board_id,
            'web_hook_enable' => true    
        ])->first();
        if($data){
            if($this->UpdateHook($data->web_hook_id,$request->list_id,$request->board_id)){
                $data->web_hook_enable=false;
                $data->web_hook_id='';
                $data->save();
                return 1;
            }
            return 0;
        }
        if($this->SaveHook($request->list_id)){
            return 1;
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

    public function SaveHook(string $list_id){
        $response=app('trello')->RegisterHookList($list_id);
        $hook_data=json_validator($response);
        if(count($hook_data)>0 &&  $hook_data){
            BoardList::where('trello_list_id' , '=' ,$list_id)->update([
                'web_hook_id' => $hook_data['id'],
                'web_hook_enable' => true
            ]);
            return 1;
        }
        return 0;
    }
    
    public function ok(){
        return 1;
    }

    public function Listentrigger(){
        $data=json_decode(request()->getContent(), true);
        if($data['model']['id']!=$data['action']['data']['old']['idList']){
            if(array_key_exists('action',$data)){
                if(array_key_exists('data',$data['action'])){
                    if(array_key_exists('card',$data['action']['data'])){
                        $card_id=$data['action']['data']['card']['id'];
                        $response=app('trello')->getCardChecklists($card_id);
                        if(count($response)>0){
                            $checklist_array = array_column($response, 'checkItems');
                            foreach($checklist_array as $k => $value){
                                foreach($value as $checklist){
                                    if($checklist['state']=='incomplete'){
                                        app('trello')->addLable($card_id);
                                        break;
                                    }
                                }
                            }
                        }    
                    }
                }
            }
        }
        return 0;
    }



}
