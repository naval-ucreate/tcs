<?php 
namespace App\Repositories\Lists;
use App\Models\BoardList;

abstract class ListClass {

    protected $model;
    
    public function __construct(BoardList $model){
        $this->model = $model;
    }

    public function create(array $attributes){
        return $this->model->create($attributes);
    }

    public function update(string $list_id, array $attributes){
        return $this->model->where('trello_list_id', '=', $list_id)
        ->update($attributes);
    }
    
    public function find($id){
        return $this->model->find($id);
    }

    public function detete($id){
        return $this->model->where('id', '=', $id)->delete();
    }
}