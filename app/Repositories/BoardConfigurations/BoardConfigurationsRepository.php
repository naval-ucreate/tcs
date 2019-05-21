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

    public function boardConfigByListId(string $list_id) {
        $this->model->where('list_id', '=', $list_id)
        ->first();
    }



}