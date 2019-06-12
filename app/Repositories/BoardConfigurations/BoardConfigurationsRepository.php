<?php 
namespace App\Repositories\BoardConfigurations;

use App\Repositories\BoardConfigurations\BoardConfigurationsClass;


class BoardConfigurationsRepository extends BoardConfigurationsClass
{

    public function boardConfigByType(int $board_id, int $type){
        return $this->model->where([
                'board_id' => $board_id,
                'type' => $type
            ])
            ->first();
    }

     public function boardConfigByTypeAndList(int $board_id, int $type, int $list_id){
        return $this->model->where([
                'board_id' => $board_id,
                'type' => $type,
                'list_id' => $list_id
            ])
            ->first();
    }

    public function boardConfigByTypeAll(int $board_id, int $type){
        return $this->model->where([
            'board_id' => $board_id,
            'type' => $type
        ])
        ->get();
    }

    public function boardConfigByListId(int $list_id) {
        return $this->model->with('board:owner_token')
        ->where('list_id', '=', $list_id)
        ->first();
    }

    public function insert(Array $attributes){
        return $this->model->insert($attributes);
    }

    public function getConfigByListId(int $list_id, int $type){
        return $this->model->with('board')
        ->where([
            'list_id' => $list_id,
            'type' => $type
        ])
        ->first();
    }

    public function boardConfigByTypeArray(int $board_id, Array $type){
        return $this->model->with('list')
        ->with('board')
        ->where('board_id', '=', $board_id)
        ->whereIn('type', $type)
        ->get();
    }

    public function getperformancelist() {
        return $this->model
        ->with(['board' => function($query) {
            $query->where('owner_token', '!=', '');
        }])
        ->with('list')
        ->where([
            'type' => 6,
            'status' => true
        ])
        ->get();
    }



}