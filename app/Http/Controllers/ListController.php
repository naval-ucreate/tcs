<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;

class ListController extends Controller
{
    

    public function store(array $data,$id){
        if(BoardList::insert($data)){
            return BoardList::where('trello_board_id','=',$id)->get()->toArray();;  
        }
    }

    public function view(){

    }

    public function select($id){

    }

    public function TrelloList(String $id){       
        //$board_list          =   BoardList::where('trello_board_id','=',$id)->get()->toArray();

        $board_list          =   BoardList::with('board')->get()->toArray();
        if(!count($board_list)){
            $list_data      =   app('trello')->GetBoardList($id);
            //dd($list_data);
            if(count($list_data)){
                foreach($list_data['lists'] as $list_val){
                    $insert_data[] = [
                    'trello_board_id'=>$list_val['idBoard'],
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
        

}
