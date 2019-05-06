<?php 
namespace App\Repositories\UserBoards;
use App\Repositories\UserBoards\UserBoardClass;


class UserBoardRepository extends UserBoardClass
{
   
    public function getUserBoards($user_trello_id){
        return $this->model->with('boards')
        ->where('user_id','=', $user_trello_id)
        ->select(['trello_board_id', 'is_admin'])
        ->get();  
    }

    public function insertUserBoard(array $data){
        $data = $this->getNewBoard($data);
        if(count($data)){
            return $this->model->insert($data);
        }
        return true;
    }

    public function deleteMany(Array $ids){
        return $this->model->whereIn('trello_board_id', '=', $ids)
        ->delete();
    }

    private function getNewBoard(array $data){
        $db_data = $this->exitsUserBoards();
        $db_data = array_column($db_data, 'trello_board_id');
        $filter_boards = [];
        foreach($data as $key => $value):
            if(!in_array($value['trello_board_id'], $db_data)) {
                $filter_boards[] = $value;
            }
        endforeach;
        return $filter_boards; 
    }

}