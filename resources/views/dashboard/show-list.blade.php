@extends('layouts.dashboard')
@section('pageTitle', $board_list[0]['board']['name'])
@section('content')
    <?php  
        $backgroud_image    =  $board_list[0]['board']['background_image'];
    ?>
      
    <div id="board_data" rel='{{ $backgroud_image }}'> </div>
 
                    <!-- START CONTENT FRAME TOP -->
                              
                                                                    
                        <div class="pull-right">
                            <button class="btn btn-default content-frame-left-toggle"><span class="fa fa-bars"></span></button>
                        </div>                                
                     
                           
                    <!-- END CONTENT FRAME TOP -->
                    
                    <!-- START CONTENT FRAME BODY -->
                    <div class="content-frame-body listing_view">
                  
                                                
                        <div class="row">
              
                        <div class="col-md-4" style="padding-top:50px">
                           
                        </div>
                        <div class="col-md-4" style="padding-top: 50px;">
                        <div style="display:none;" class="loading_loader">
                        <img src="{{ URL::asset('jolly/img/loading.gif') }}" style="position: absolute;z-index: 999;padding-top: 125px;padding-left: 206px;">
                        </div>  
                        <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title">Lists</h3>         
                                     <!--  <ul class="panel-controls">
                                        <li><a href="#" class="control-primary"><span class="fa fa-plus"></span></a></li>
                                    </ul> -->
                                </div>
                                <div class="panel-body list-group list-group-contacts"> 
                                <span id="reg_url" style="display:none">{{ route('register_hook') }}</span>
                                <span id="delete_hook" style="display:none">{{ route('delete_hook') }}</span>
                                     @foreach($board_list as $list)                                                                                        
                                    <a href="#" class="list-group-item">
                                      
                                        <div id="_{{$list['trello_list_id']}}" class="list-group-status status-{{ ($list['web_hook_enable']==1?'online':'offline') }}"></div>
                                      <!--  <img src="assets/images/users/user4.jpg" class="pull-left" alt="Brad Pitt"> -->
                                        <h2 class="contacts-title" style="margin-top: 10px;"> {{$list['name']}}
                                            <span id="{{$list['trello_list_id']}}">
                                                @if($list['web_hook_enable']==1)
                                                 <button class="btn btn-danger btn-sm delete-hook" rel="something"> Remove Hook </button>
                                                @endif
                                            </span>
                                        </h2>
                                        <div class="list-group-controls">
                                          <input type="radio"   {{($list['web_hook_enable']==1?'checked':'')}} name="list_id" value="{{$list['trello_list_id']}}~{{$list['trello_board_id']}}">
                                        </div>
                                    </a>
                                    @endforeach
                            </div>
                            </div>
                            </div>                      
                        </div>    
                          
                                                
                    </div>
                    <!-- END CONTENT FRAME BODY -->
                    
                </div>
                <!-- END CONTENT FRAME -->

            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

             
@endsection
<script src="{{url('js/script/list.js')}}"></script>
