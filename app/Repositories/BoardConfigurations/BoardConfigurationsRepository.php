<?php 
namespace App\Repositories\BoardConfigurations;

use App\Repositories\BoardConfigurations\BoardConfigurationsClass;


class BoardConfigurationsRepository extends BoardConfigurationsClass
{

    public function boardConfigByType(string $board_id, int $type){
        return $this->model->where([
                'board_id' => $board_id,
                'type' => $type
            ])
            ->first();
    }

    public function boardConfigByTypeAll(string $board_id, int $type){
        return $this->model->where([
            'board_id' => $board_id,
            'type' => $type
        ])
        ->get();
    }

    public function boardConfigByListId(string $list_id) {
        return $this->model->with('board:owner_token')
        ->where('list_id', '=', $list_id)
        ->first();
    }

    public function insert(Array $attributes){
        return $this->model->insert($attributes);
    }

    public function getConfigByListId(String $list_id, int $type){
        return $this->model->with('board:owner_token')
        ->where([
            'list_id' => $list_id,
            'type' => $type
        ])
        ->first();
    }

    public function boardConfigByTypeArray(String $board_id, Array $type){
        return $this->model->where('board_id', '=', $board_id)
        ->whereIn('type', $type)
        ->get();
    }



}