@extends('layouts.dashboard')
@section('pageTitle', 'Boards')
@section('content')

<div class="panel panel-info">
   <div class="panel-heading">
      <center><h4 class="panel-success"> Boards</h4></center>
   </div> 
   <div class="panel-body"> 
    <div class="row ">
            @forelse ($userBoardsData as $userBoardsVal)   
            <div class="col-md-4">       
                <div class="panel panel-info">
                            <div class="panel-heading ui-draggable-handle">
                                <h3 class="panel-title"><a style="color:skyblue" href="list/{{$userBoardsVal->trello_board_id}}" >{{ $userBoardsVal->name }}</a></h3>
                                <button class="btn btn-info pull-right delete_data">Members</button>
                            </div>
                            <div class="panel-body">
                                {{ $userBoardsVal->name }}
                            </div>      
                            <div class="panel-footer">
                                <button class="btn btn-primary pull-center delete_data">Edit</button>
                                
                                <button class="btn btn-danger pull-right delete_data" rel="{{$userBoardsVal->id}}" model="Board" >Delete</button>
                            </div>                            
                        </div>

                </div>
            @empty
            <p>No Board </p>
            @endforelse           

    </div>  
 </div>
</div>

@endsection
<script src="{{url('js/script/board.js')}}"></script>
