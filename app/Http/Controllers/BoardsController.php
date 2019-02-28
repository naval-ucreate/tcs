<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client as HttpClient;
class BoardsController extends Controller
{
 
    public function showBoards1()
    {
        return view('dashboard/show-board');
    }
    public function showBoards()
    {
        $userInfo    =    Session::get('userinfo');
        $oAuthToken  =    $userInfo['token'];
        $boardsData  =    app('trello')->getUserBoards($oAuthToken);
        if(count($boardsData))
        {
            foreach($boardsData as $boardVal)
            {
                    $insertData     =     ['trello_user_id'=>$userInfo['trello_id'],
                                        'user_id'=>$userInfo['id'],
                                        'name'=> $boardVal['name'],
                                        'image'=> $boardVal['prefs']['backgroundImage'],
                                        'trello_board_id'=> $boardVal['id'],
                                        'members'=> json_encode($boardVal['memberships']),];                      
                $this->addUpdateBoard($insertData);              
                                    
            }
            $userBoardsData      =   Board::where('user_id',$userInfo['id'])->get();  
            return view('dashboard/show-board',compact('userBoardsData'));
        }
    }
    function addUpdateBoard($insertData)
    {
        $authUserCount   =   Board::where('user_id',$insertData['user_id'])
                                ->where('trello_board_id',$insertData['trello_board_id'])
                                ->count();
        if($authUserCount>0)
        {
            Board::where('trello_board_id',$insertData['trello_board_id'])->update($insertData);
            return true;
        }
        Board::create($insertData);        
        return true;
    }
}
