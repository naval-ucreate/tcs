<?php 

namespace App\Repositories\Lists;
use App\Repositories\Lists\ListClass;

class ListRepository extends ListClass
{


    public function findByTrelloBoardId($board_id){
        return $this->model->with('board')
        ->with('boardConfig')
        ->where('trello_board_id','=',$board_id)->get()->toArray();
    }
    
    public function insertMany($data){
        return $this->model->insert($data);
    }

    public function deleteMany(array $list_ids){
        return $this->model->whereIn('trello_list_id', '=', $list_ids)->delete();
    }

    public function disbaleAll(string $board_id){
        return $this->model->where('trello_board_id' ,'=', $board_id)
        ->update(['checklist_enable' => false]);
    }

    public function findByListId(string $list_id){
        return $this->model->with('board:owner_token,trello_board_id')
        ->where('trello_list_id', '=', $list_id)
        ->first();
    }

    public function disbaleAllBug(string $board_id){
        return $this->model->where('trello_board_id' ,'=', $board_id)
        ->update(['bug_enable' => false]);
    }

    public function updateListBug(Array $list_ids){
        return $this->model->whereIn('trello_list_id' , $list_ids)
        ->update(['bug_enable' => true]);
    }

}