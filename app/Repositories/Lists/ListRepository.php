<?php 

namespace App\Repositories\Lists;
use App\Repositories\Lists\ListClass;

class ListRepository extends ListClass
{


    public function findByTrelloBoardId($board_id){
        return $this->model->with('board')
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
        ->update(['web_hook_enable' => false]);
    }
}