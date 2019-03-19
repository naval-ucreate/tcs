@extends('layouts.dashboard')
@section('pageTitle', 'Boards')
@section('content')

<div class="panel panel-info">
   <div class="panel-heading">
      <center><h4 class="panel-success"> Boards</h4></center>
   </div> 
   <div class="panel-body"> 
    <div class="row ">
            @forelse ($user_boards as $user_boards_val)   
            <?php $is_admin=array_exists($user_boards_val->members,Auth::user()->trello_id); ?>
            <div class="col-md-3 " style="{{$is_admin?'':'opacity: 0.6;'}}">       
                <div class="panel  {{$is_admin?'panel-info':'panel-danger'}}"  >

                               
                            <a href="{{$is_admin?route('lists',[$user_boards_val->trello_board_id]):'#'}}">
                                <div class="panel-body" style='height:100px;{{ ($user_boards_val->background_image) ? "background-image:url($user_boards_val->background_image)" : "background-color: rgb(0, 121, 191);"}}' >
                                <h3 style="color:white"> <center> {{ $user_boards_val->name }} </center> </h3>
                                </div>
                            </a> 
                            @if($is_admin)     
                               <!-- <div class="panel-footer footer-info" style='{{ ($user_boards_val->background_image) ? "background-image:url($user_boards_val->background_image)" : "background-color: rgb(0, 121, 191);"}}'>
                                        <button class="btn btn-danger pull-right delete_data" rel="{{$user_boards_val->id}}" model="Board" >Delete</button>
                                </div>  --> 
                            @endif                             
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
