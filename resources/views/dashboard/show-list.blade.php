@extends('layouts.dashboard')
@section('pageTitle', 'Lists')
@section('content')
    <?php  
    // $backgroud_image=json_encode($list_data['prefs']);  
    $backgroud_image    =  $board_list[0]['board']['background_image'];
    //$backgroud_image;
    ?>

    <div id="board_data" rel='{{ $backgroud_image }}'> </div>
    <div class="content-frame listing_view">     
                    <!-- START CONTENT FRAME TOP -->
                              
                                                                    
                        <div class="pull-right">
                            <button class="btn btn-default content-frame-left-toggle"><span class="fa fa-bars"></span></button>
                        </div>                                
                        <!-- <div class="pull-right" style="width: 100px; margin-right: 5px;">
                            <select class="form-control select">
                                <option>All</option>                                
                                <option>Work</option>
                                <option>Home</option>
                                <option>Friends</option>
                                <option>Closed</option>
                            </select>
                        </div> -->
                        
                                  
                    <div class="content-frame-left">
                        <div class="form-group">
                            <h4>Add new task:</h4>
                            <textarea class="form-control push-down-10" id="new_task" rows="4" placeholder="Add new List"></textarea>                            
                            <center> <button class="btn btn-info" id="add_new_task"><span class="fa fa-edit"></span> Add</button></center>
                        </div>                        
                        <div class="form-group push-up-10">
                            <h4>Searh in tasks:</h4>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="fa fa-search"></span></div>
                                <input type="text" class="form-control" placeholder="keyword..."/>
                            </div>
                        </div>
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

        <!-- MODALS -->        
        <div class="modal fade" id="taskEdit" tabindex="-1" role="dialog" aria-labelledby="taskEditModalHead" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="taskEditModalHead">Edit Task</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Task description</label>
                            <textarea class="form-control" id="task-text" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Task group</label>
                            <select class="form-control select">
                                <option>Work</option>
                                <option>Home</option>
                                <option>Friends</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>        
@endsection
<script src="{{url('js/script/list.js')}}"></script>
