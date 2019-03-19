@extends('layouts.dashboard')
@section('pageTitle', $board_list[0]['board']['name'])
@section('content')
    <?php  
        $backgroud_image    =  $board_list[0]['board']['background_image'];
    ?>
      
    <div id="board_data" rel='{{ $backgroud_image }}'> </div>
 
                              
                                                                    
                        <div class="pull-right">
                            <button class="btn btn-default content-frame-left-toggle"><span class="fa fa-bars"></span></button>
                        </div>                                
                     
                    <div class="content-frame-body listing_view">
                        <div class="hook-alert"> No Hook Register in this board</div>
                                                
                        <div class="row">
              
                        <div class="col-md-4" style="padding-top:50px">
                           
                        </div>
                        <div class="col-md-4" style="padding-top: 50px;">
                        <div style="display:none;" class="loading_loader">
                        <img src="{{ URL::asset('jolly/img/loading.gif') }}" style="position: absolute;z-index: 999;padding-top: 125px;padding-left: 206px;">
                        </div>  
                        <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title text-center">{{$board_list[0]['board']['name']}}</h3>         
                                </div>
                                <div class="panel-body list-group list-group-contacts"> 
                                <span id="reg_url" style="display:none">{{ route('register_hook') }}</span>
                                <span id="delete_hook" style="display:none">{{ route('delete_hook') }}</span>

                                     @foreach($board_list as $list)                                                                                        
                                            <a href="#" class="list-group-item {{$list['web_hook_enable']?'active':''}}">
                                                <span class="add_hook">
                                                    <div id="_{{$list['trello_list_id']}}" class="list-group-status  status-{{ ($list['web_hook_enable']==1?'online':'offline') }}"></div>
                                                        <h2 class="contacts-title" style="margin-top: 10px;"> {{$list['name']}}
                                                        
                                                        </h2>
                                                        <div class="list-group-controls _checkbox">
                                                        @if($list['web_hook_enable']==1)   
                                                            <i  class="fa fa-check" aria-hidden="true"></i> 
                                                            <input type="hidden" value="1" name="hook_checked"/>
                                                        @endif   
                                                        <input style="display:none;" type="radio"   {{($list['web_hook_enable']==1?'checked':'')}} name="list_id" value="{{$list['trello_list_id']}}~{{$list['trello_board_id']}}">
                                                    </div>
                                                </span>
                                                <span class="list-group-controls"  id="{{$list['trello_list_id']}}">
                                                            @if($list['web_hook_enable']==1)
                                                                <button class="btn btn-danger btn-sm delete-hook" rel="something"> Remove Hook </button>
                                                            @endif
                                                </span>
                                            </a>
                                     @endforeach
                            </div>
                            </div>
                            </div>                      
                        </div>    
                          
                                                
                    </div>
                
                    
                </div>
              

            </div>            
           
        </div>
       

             
@endsection
<script src="{{url('js/script/list.js')}}"></script>
