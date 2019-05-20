<?php 
namespace App\Repositories\BoardActivities;
use DB;
use App\Models\BoardActivity;

abstract class BoardActivitiesClass {

    protected $model;
    
    public function __construct(BoardActivity $model){
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

    


}