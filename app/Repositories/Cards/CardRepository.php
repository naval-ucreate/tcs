<?php 
namespace App\Repositories\Cards;

use App\Repositories\Cards\CardClass;


class CardRepository extends CardClass
{

   public function findByCardId(string $card_id){
        return $this->model->where('trello_card_id', '=', $card_id)
        ->first();
   }

   public function updateByCard(string $card_id, array $attribute){
        return (boolean) $this->model->where('trello_card_id', '=', $card_id)
        ->update($attribute);
   }
    
   
}