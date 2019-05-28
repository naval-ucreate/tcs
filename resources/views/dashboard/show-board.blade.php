@extends('layouts.dashboard')
@section('pageTitle', 'Boards')
@section('content')



<div class="panel panel-info">
   
   <div class="panel-heading">
     <h2 class="panel-success">Boards </h2>
   </div> 
   <div class="panel-body"> 

   <div class="panel-heading">
   <?php
        $hook_register = '';
        $user_admin = '';
        $only_member = ''; 
   ?>     
        @foreach ($user_boards as $user_boards_val)
                
                <?php $background = ($user_boards_val->boards->background_image) ?  "background-image:url(".$user_boards_val->boards->background_image.")"  : "background-color: rgb(0, 121, 191);" ?>
                <?php $admin_class = $user_boards_val->is_admin ? 'panel-info':'panel-danger'; ?>
                <?php $board_name = $user_boards_val->boards->name; ?>
                
                @if($user_boards_val->is_admin)
                        <?php 
                                $report_enable = (!$user_boards_val->boards->web_hook_enable)?'Enabled Report':'Disabled Report';
                                $report_class =  (!$user_boards_val->boards->web_hook_enable)?'btn-info':'btn-danger';
                        ?>
                        <?php $user_admin .= '<div class="col-md-3">       
                                                        <div class="panel  '.$admin_class.'"  >
                                                            <div class="panel-body" style="height:100px;'. $background .'" >
                                                                <h3 style="color:white"> <center> '. $board_name .' </center> </h3>
                                                            </div>
                                                            <div class="panel-footer footer-info" style="'.$background.'">
                                                                <!-- <button class="btn btn-danger pull-right delete_data" 
                                                                rel="{{$user_boards_val->id}}" model="Board" >Delete</button> -->
                                                                <a href="'.route("lists",[$user_boards_val->trello_board_id]).'" class="btn btn-primary pull-right" >Settings </a> 
                                                                <a href="'.route('activity',[$user_boards_val->trello_board_id]).'" class="btn btn-info pull-right" style="margin-right:10px" >View Report</a>
                                                                <a href="javascript:void(0)" class="btn '.$report_class.' btn-sm pull-left _register" 
                                                                status = "'.$user_boards_val->boards->web_hook_enable.'" 
                                                                data="'.$user_boards_val->trello_board_id.'">'.$report_enable.' 
                                                                <i class="fa fa-spinner fa-spin icon_show" style="display:none;" aria-hidden="true"></i></a>
                                                            </div> 
                                                        </div>
                                                </div>' 
                        ?>
                @endif
                @if(!$user_boards_val->is_admin)

                        <?php $only_member .= '<div class="col-md-3 ">       
                                                        <div class="panel  '.$admin_class.'"  >
                                                            <div class="panel-body" style="height:100px;'. $background .'" >
                                                                <h3 style="color:white"> <center> '. $board_name .' </center> </h3>
                                                            </div>
                                                            <div class="panel-footer footer-info" style="'.$background.'">
                                                                <a href="'.route('activity',[$user_boards_val->trello_board_id]).'" class="btn btn-info pull-right" style="margin-right:10px" >View Report</a>     
                                                            </div> 
                                                        </div>
                                                </div>'  
                        ?>
                @endif
        @endforeach
       
 
        <!-- <h2 class="panel-success">Hook Enable </h2>
   
   
        <div class="row">
        
                <?= $hook_register ?>
        </div>   -->
        <hr>        
        <h2 class="panel-success">  My Boards</h2>
        <div class="row">
                <?= $user_admin ?>
        </div>  
       
        <div class="panel-heading">
                <h2 class="panel-success">  Member of Boards</h2>
        </div> 
        <div class="row ">
                <?= $only_member ?>
        </div>  
        </div>
</div>

@endsection
<script src="{{url('js/script/board.js')}}"></script>
