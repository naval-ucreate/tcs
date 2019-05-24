<?php 
namespace App\Repositories\BoardMembers;

use App\Repositories\BoardMembers\BoardMemberClass;


class BoardMemberRepository extends BoardMemberClass
{
    public function insert(Array $attribute){
        return $this->model->insert($attribute); 
    }
}