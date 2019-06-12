<?php 
namespace App\Repositories\BoardConfigurations;
use DB;
use App\Models\BoardConfiguration;

abstract class BoardConfigurationsClass {

    protected $model;
    
    public function __construct(BoardConfiguration $model){
        $this->model = $model;
    }

    public function create(array $attributes){
        return $this->model->create($attributes);
    }

    public function update(string $borad_id, array $attributes){
        return (boolean) $this->model->where('board_id', '=', $borad_id)
        ->update($attributes);
    }
    
    public function find($id){
        return $this->model->find($id);
    }

    public function detete($id){
        return $this->model->where('id', '=', $id)->delete();
    }

    abstract public function getperformancelist();    
}