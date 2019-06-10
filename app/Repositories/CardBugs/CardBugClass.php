<?php 
namespace App\Repositories\CardBugs;
use App\Models\CardBugs; 

abstract class CardBugClass {

    protected $model;
    
    public function __construct(CardBugs $model){
        $this->model = $model;
    }

    public function create(array $attributes){
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes){
        return (boolean) $this->model->where('id', '=', $borad_id)
        ->update($attributes);
    }
    
    public function find($id){
        return $this->model->find($id);
    }

    public function detete($id){
        return $this->model->where('id', '=', $id)->delete();
    }


}