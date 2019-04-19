<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;
use App\Models\TestingHook;

class ListController extends Controller
{
    
    public function store(array $data,$id){
        if(BoardList::insert($data)){
            return BoardList::with('board')->where('trello_board_id','=',$id)->get()->toArray();  
        }
    }
    public function TrelloList(String $id){       
        $board_list = BoardList::with('board')->where('trello_board_id','=',$id)->get()->toArray();
        if(!count($board_list)){
            $list_data  =   app('trello')->GetBoardList($id);
            if(count($list_data)){
                foreach($list_data['lists'] as $list_val){
                    $insert_data[] = [
                    'trello_board_id'=>$id,
                    'trello_list_id'=>$list_val['id'],
                    'name'=> $list_val['name'],
                    'web_hook_enable'=>0,
                    ];
                }
                $board_list   =  $this->store($insert_data,$id); 
                return view('dashboard/show-list',compact('board_list'));
            } 
        }
        return view('dashboard/show-list',compact('board_list'));         
    }

    public function testWebHook(){
        $data=json_decode(request()->getContent(), true);
        TestingHook::create([
            'details' => json_encode($data)
        ]);
        return 1;
    }

}
