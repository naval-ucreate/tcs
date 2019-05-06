<?php 
namespace App\Repositories\UserBoards;
use DB;
use App\Models\UsersBoard as UserBoard;
use Illuminate\Support\Facades\Auth;

abstract class UserBoardClass {

    protected $model;
    
    public function __construct(UserBoard $model){
        $this->model = $model;
    }

    public function create(array $attributes){
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes){
        $user = $this->model->findOrFail($id);
        return (boolean) $user->whereId($id)->update($attributes);
    }
    
    public function find($id){
        return $this->model->find($id);
    }

    public function detete($id){
        return $this->model->where('id', '', $id)->delete();
    }

    protected function exitsUserBoards(){
        return $this->model
        ->select('trello_board_id')
        ->where('user_id', Auth::user()->trello_id)
        ->get()
        ->toArray();
    }


}