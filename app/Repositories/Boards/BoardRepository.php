<?php 
namespace App\Repositories\Boards;

use App\Repositories\Boards\BoardClass;
use App\Models\Board as Board;

class BoardRepository extends BoardClass
{

   
    public function insert(Array $data){
        $new_boards = $this->getNewBoard($data);
        if(count($new_boards)>0) {
            return $this->model->insert($new_boards);    
        }
        return true;
    }

    public function deleteMany(Array $ids){
        return $this->model->whereIn('trello_board_id','=',$ids)
        ->delete();
    }

    public function getBoardId(int $board_id) {
        return $this->model->where('id' ,'=', $board_id)
        ->first();
    }

    private function getNewBoard(Array $data){
        $board_ids = array_column($data, 'trello_board_id');
        $db_data = $this->exitsBoards($board_ids);
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