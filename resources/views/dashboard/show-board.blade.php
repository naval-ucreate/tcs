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
                <div class="panel panel-info"  >
                            <a href="{{route('lists',[$userBoardsVal->trello_board_id])}}">
                                <div class="panel-body" style='height:100px;{{ ($userBoardsVal->backgroundImage) ? "background-image:url($userBoardsVal->backgroundImage)" : "background-color: rgb(0, 121, 191);"}}' >
                                <h3 style="color:white"> <center> {{ $userBoardsVal->name }} </center> </h3>
                                </div>
                            </a>      
                            <div class="panel-footer footer-info" style='{{ ($userBoardsVal->backgroundImage) ? "background-image:url($userBoardsVal->backgroundImage)" : "background-color: rgb(0, 121, 191);"}}'>
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
