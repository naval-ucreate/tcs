<?php 

namespace App\Repositories\CardBugs;

use App\Repositories\CardBugs\CardBugClass;
use DB;

class CardBugsRepository extends CardBugClass {

    public function getBugCount(int $board_id, String $date = ''){
        return $this->model->where([
            'board_id' => $board_id,
            'type' => 2
        ])->sum('total');
    }

    public function getRevertCount(int $board_id, String $date = ''){
        return $this->model->where([
            'board_id' => $board_id,
            'type' => 1
        ])->sum('total');
    }

    public function getCardCount(int $board_id, String $date = ''){
        return $this->model->where('board_id', '=', $board_id)
        ->groupBy('card_id')
        ->count();
    }

}