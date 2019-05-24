<?php 
namespace App\Repositories\BoardMembers;
use App\Models\BoardMember;

abstract class BoardMemberClass {

    protected $model;
    
    public function __construct(BoardMember $model){
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
        return $this->model->where('board_id', '=', $id)->delete();
    }

    protected function findMembers(string $board_id){
        return $this->model->where('board_id', '=', $board_id)
        ->get();
    }


}