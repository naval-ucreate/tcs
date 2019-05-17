<?php 
namespace App\Repositories\Cards;

use App\Repositories\Cards\CardClass;


class CardRepository extends CardClass
{

   public function findByCardidAndBoardId(string $board_id, string $card_id){
        return $this->model->where([
            'trello_board_id' => $board_id,
            'trello_card_id' => $card_id
        ])->first();
   }

   public function updateByBoardAndCard(string $board_id, string $card_id, array $attribute){
        return (boolean) $this->model->where([
            'trello_board_id' => $board_id,
            'trello_card_id' => $card_id
        ])->update($attributes);
   }
    

}