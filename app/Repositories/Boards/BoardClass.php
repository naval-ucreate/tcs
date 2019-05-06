<?php 
namespace App\Repositories\Boards;
use DB;
use App\Models\Board as Board;

abstract class BoardClass {

    protected $model;
    
    public function __construct(Board $model){
        $this->model = $model;
    }

    public function create(array $attributes){
        return $this->model->create($attributes);
    }

    public function update(string $borad_id, array $attributes){
        return (boolean) $this->model->where('trello_board_id', '=', $borad_id)
        ->update($attributes);
    }
    
    public function find($id){
        return $this->model->find($id);
    }

    public function detete($id){
        return $this->model->where('id', '=', $id)->delete();
    }

    protected function exitsBoards(array $array){
        return $this->model->whereIn('trello_board_id',$array)
        ->get()
        ->toArray();
    }


}