@extends('layouts.dashboard')
@section('pageTitle', 'Lists')
@section('content')
    <?php  
        $backgroud_image    =  $board_list[0]['board']['background_image'];
    ?>

    <div id="board_data" rel='{{ $backgroud_image }}'> </div>
    <div class="content-frame listing_view">     
                    <!-- START CONTENT FRAME TOP -->
                              
                                                                    
                        <div class="pull-right">
                            <button class="btn btn-default content-frame-left-toggle"><span class="fa fa-bars"></span></button>
                        </div>                                
                     
                                  
                    <div class="content-frame-left">
                        <div class="form-group">
                            <h4>Add new task:</h4>
                            <textarea class="form-control push-down-10" id="new_task" rows="4" placeholder="Add new List"></textarea>                            
                            <center> <button class="btn btn-info" id="add_new_task"><span class="fa fa-edit"></span> Add</button></center>
                        </div>                        
                        <!-- <div class="form-group push-up-10">
                            <h4>Searh in tasks:</h4>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="fa fa-search"></span></div>
                                <input type="text" class="form-control" placeholder="keyword..."/>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <h4>Members:</h4>
                            <div class="list-group border-bottom">
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <h4>Tags:</h4>
                            <!-- <ul class="list-tags">
                                <li><a href="#"><span class="fa fa-tag"></span> amet</a></li>
                                <li><a href="#"><span class="fa fa-tag"></span> rutrum</a></li>
                                <li><a href="#"><span class="fa fa-tag"></span> nunc</a></li>
                                <li><a href="#"><span class="fa fa-tag"></span> tempor</a></li>
                                <li><a href="#"><span class="fa fa-tag"></span> eros</a></li>
                                <li><a href="#"><span class="fa fa-tag"></span> suspendisse</a></li>
                                <li><a href="#"><span class="fa fa-tag"></span> dolor</a></li>
                            </ul>                             -->
                        </div>
                        
                    </div>       
                    <!-- END CONTENT FRAME TOP -->
                    
                    <!-- START CONTENT FRAME BODY -->
                    <div class="content-frame-body listing_view">
                                                
                        <div class="row">
              
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                        <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title">Lists</h3>         
                                     <!--  <ul class="panel-controls">
                                        <li><a href="#" class="control-primary"><span class="fa fa-plus"></span></a></li>
                                    </ul> -->
                                </div>
                                <div class="panel-body list-group list-group-contacts"> 
                                <span id="reg_url" style="display:none">{{ route('register_hook') }}</span>
                                    <?php 
                                   /* echo "<pre>";
                                    print_R($board_list);
                                    echo "</pre>";              */                     
                                    ?>
                                     @foreach($board_list as $list)                                                                                        
                                    <a href="#" class="list-group-item">                                 
                                        <div class="list-group-status status-{{ ($list['web_hook_enable']==1?'online':'offline') }}"></div>
                                      <!--  <img src="assets/images/users/user4.jpg" class="pull-left" alt="Brad Pitt"> -->
                                        <h2 class="contacts-title" style="margin-top: 10px;"> {{$list['name']}}</h2>
                                        <div class="list-group-controls">
                                          <input type="radio"  {{($list['web_hook_enable']==1?'checked':'')}} name="list_id" value="{{$list['trello_list_id']}}~{{$list['trello_board_id']}}">
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
