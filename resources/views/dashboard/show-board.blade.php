@extends('layouts.dashboard')
@section('pageTitle', 'Boards')
@section('content')
<div class="col-md-12">
<!-- DATA TABLE -->
<h3 class="title-5 m-b-35">Boards</h3>                             

<div class="page-content-wrap">                

                               
           
                    <div class="row">
                    @forelse ($userBoardsData as $userBoardsVal)   
                    <div class="col-md-6">       
                        <div class="panel panel-default">
                                    <div class="panel-heading ui-draggable-handle">
                                        <h3 class="panel-title">{{ $userBoardsVal->name }}</h3>
                                    
                                    </div>
                                    <div class="panel-body">
                                    {{ $userBoardsVal->name }}
                                    </div>     
                         
                                    <div class="panel-footer">
                                        <button class="btn btn-success pull-right update_data" rel="{{$userBoardsVal->id}}" model="Board" >Update</button>  
                                        <button class="btn btn-danger pull-right delete_data" rel="{{$userBoardsVal->id}}" model="Board" >Delete</button>
                                    </div>                                                            
                                </div>
                    
                        </div>
                    @empty
                    <p>No users</p>
                    @endforelse           
</div>
            </div>    
        </div>
</div>
@stop
<script src="{{url('js/script/board.js')}}"></script>
