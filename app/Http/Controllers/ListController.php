<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardList;

class ListController extends Controller
{
    

    public function store(){

    }

    public function view(){

    }

    public function select($id){

    }

    public function TrelloList(String $id){
        $list_data      =   app('trello')->GetBoardList($id);
        $list           =   Lists::where('trello_board_id','=',$id)->get();
        dd($list_data);
        return view('dashboard/trelloList',compact('list_data'));  
    }    

}
