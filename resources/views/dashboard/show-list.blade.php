@extends('layouts.dashboard')
@section('pageTitle', $board_list[0]['board']['name'])
@section('content')
    <?php
        $backgroud_image = $board_list[0]['board']['background_image'];
    ?>
   
    <div id="board_data" rel='{{ $backgroud_image }}'> </div>
                    <div class="content-frame-body listing_view">
                        <div class="hook-alert"> No configration set for this board </div>
                        <?php
                            $check_list = $pm = $qa = $dev = $production = ''
                        ?>                        
                        <div class="row">
              
                            <div class="col-md-4" style="padding-top:50px"></div>
                            <div class="col-md-4" style="padding-top: 50px;">
                                <div class="configration">    
                                    <ul class="nav nav-tabs nav-justified">
                                        <li class="active config"><a href="#tab8" data-toggle="tab" rel = "checklist" aria-expanded="false"> Checklist configration </a></li>
                                        <li class="config"><a href="#tab9" data-toggle="tab" rel = "bugs" aria-expanded="false"> Pm Configration </a></li>
                                        <li class="config"><a href="#tab9" data-toggle="tab" rel = "Qa" aria-expanded="false"> Qa Configration </a></li>
                                        <li class="config"><a href="#tab9" data-toggle="tab" rel = "Dev" aria-expanded="false"> Dev Configration </a></li>
                                        <li class="config"><a href="#tab9" data-toggle="tab" rel = "production" aria-expanded="false"> Production Configration </a></li>
                                    </ul>
                                </div>  

                                <div style="display:none;" class="loading_loader">
                                    <img src="{{ URL::asset('jolly/img/loading.gif') }}" style="position: absolute;z-index: 999;padding-top: 125px;padding-left: 206px;">
                                </div>  

                                <div class="panel panel-default" id="checklist">
                                        <div class="panel-heading ui-draggable-handle">
                                            <h3 class="panel-title text-center">{{$board_list[0]['board']['name']}}</h3>         
                                        </div>
                                        <div class="panel-body list-group list-group-contacts"> 
                                            @foreach($board_list as $list)   
                                            
                                                <?php 
                                                    $is_enable = config_enable($list['board_config'], $list['trello_list_id'], 1);
                                                ?> 
                                                                                                                                    
                                                <a href="javascript:void(0)" class="list-group-item {{ $is_enable ? 'active':''}}">
                                                    <span class="enable_report" rel="1">
                                                        <div id="_{{$list['trello_list_id']}}" class="list-group-status status-checklist status-{{ ( $is_enable ? 'online':'offline') }}"></div>
                                                            <h2 class="contacts-title" style="margin-top: 10px;"> {{$list['name']}}
                                                            
                                                            </h2>
                                                            <div class="list-group-controls _checkbox">
                                                            @if($is_enable)   
                                                                <i  class="fa fa-check checklist" aria-hidden="true"></i> 
                                                                <input type="hidden" value="1" name="hook_checked"/>
                                                            @endif   
                                                            <input style="display:none;" type="radio"   {{( $is_enable  ? 'checked':'')}} name="list_id" value="{{$list['trello_list_id']}}">
                                                        </div>
                                                    </span>
                                                    <span class="list-group-controls"  id="{{$list['trello_list_id']}}">
                                                                @if($is_enable)
                                                                    <button class="btn btn-danger btn-sm delete-hook _checklist" data = "1" rel="something"> Remove </button>
                                                                @endif
                                                    </span>
                                                </a>
                                            @endforeach
                                        </div>
                                </div>

                                <div class="panel panel-default" id="bugs" style="display:none">
                                        <div class="panel-heading ui-draggable-handle">
                                            <h3 class="panel-title text-center"> {{$board_list[0]['board']['name']}} </h3>   
                                            <button data = "2" class="btn btn-danger pull-right update_config" > Save </button>      
                                        </div>
                                        <div class="panel-body list-group list-group-contacts"> 
                                            @foreach($board_list as $list)
                                                <?php 
                                                     $is_enable = config_enable($list['board_config'], $list['trello_list_id'], 2); 
                                                ?>  
                                                <span class="add_config">                                                                                       
                                                    <a href="javascript:void(0)" class="list-group-item {{$is_enable ? 'active':''}}">
                                                        
                                                        <div id="_{{$list['trello_list_id']}}" class="list-group-status  status-{{ ( $is_enable ? 'online':'offline') }}"></div>
                                                            <h2 class="contacts-title" style="margin-top: 10px;"> 
                                                                {{$list['name']}}
                                                            </h2>
                                                            <div class="list-group-controls _checkbox">
                                                            @if($is_enable == 1)   
                                                                <i  class="fa fa-check" aria-hidden="true"></i> 
                                                                <input type="hidden" value="1" name="hook_checked"/>
                                                            @endif   
                                                            <input  type="checkbox"   {{( $is_enable  ? 'checked':'')}} name="list_id[]" value="{{$list['trello_list_id']}}">
                                                        </div>
                                                    </a>
                                                </span>
                                            @endforeach
                                        <div>
                                          
                                        </div>
                                       
                                        </div>
                                        
                                </div>
                                <div class="panel panel-default" id="Qa" style="display:none">
                                        <div class="panel-heading ui-draggable-handle">
                                            <h3 class="panel-title text-center"> {{$board_list[0]['board']['name']}} </h3>       
                                        </div>
                                        <div class="panel-body list-group list-group-contacts"> 
                                            @foreach($board_list as $list)
                                                <?php 
                                                     $is_enable = config_enable($list['board_config'], $list['trello_list_id'], 3);
                                                ?>

                                                <a href="javascript:void(0)" class="list-group-item {{ $is_enable ? 'active':''}}">
                                                        <span class="enable_report" rel="3">
                                                            <div id="_qa_{{$list['trello_list_id']}}" class="list-group-status status-qa status-{{ ( $is_enable ? 'online':'offline') }}"></div>
                                                                <h2 class="contacts-title" style="margin-top: 10px;"> {{$list['name']}}
                                                                
                                                                </h2>
                                                                <div class="list-group-controls _checkbox">
                                                                @if($is_enable)   
                                                                    <i  class="fa fa-check qa" aria-hidden="true"></i> 
                                                                    <input type="hidden" value="1" name="hook_checked"/>
                                                                @endif   
                                                                <input style="display:none;" type="radio" {{( $is_enable ? 'checked':'')}} name="list_id" value="{{$list['trello_list_id']}}">
                                                            </div>
                                                        </span>
                                                        <span class="list-group-controls"  id="qa_{{$list['trello_list_id']}}">
                                                                @if($is_enable)
                                                                    <button class="btn btn-danger btn-sm delete-hook _qa" data = "3" rel="something"> Remove </button>
                                                                @endif
                                                        </span>
                                                </a>
                                            @endforeach
                                        <div>
                                          
                                        </div>
                                       
                                        </div>
                                        
                                </div>
                                <div class="panel panel-default" id="Dev" style="display:none">
                                        <div class="panel-heading ui-draggable-handle">
                                            <h3 class="panel-title text-center"> {{$board_list[0]['board']['name']}} </h3>   
                                            <button data="4" class="btn btn-danger pull-right update_config" > Save </button>      
                                        </div>
                                        <div class="panel-body list-group list-group-contacts"> 
                                            @foreach($board_list as $list)
                                                <?php 
                                                     $is_enable = config_enable($list['board_config'], $list['trello_list_id'], 4);
                                                ?> 
                                                <span class="add_config">                                                                                       
                                                    <a href="javascript:void(0)" class="list-group-item {{ $is_enable ? 'active':''}}">
                                                        
                                                        <div id="_{{$list['trello_list_id']}}" class="list-group-status  status-{{ ( $is_enable ? 'online':'offline') }}"></div>
                                                            <h2 class="contacts-title" style="margin-top: 10px;"> 
                                                                {{$list['name']}}
                                                            </h2>
                                                            <div class="list-group-controls _checkbox">
                                                            @if( $is_enable == 1)   
                                                                <i  class="fa fa-check" aria-hidden="true"></i> 
                                                                <input type="hidden" value="1" name="hook_checked"/>
                                                            @endif   
                                                            <input  type="checkbox"   {{( $is_enable  ? 'checked' :'' )}} name="list_id[]" value="{{$list['trello_list_id']}}">
                                                        </div>
                                                    </a>
                                                </span>
                                            @endforeach
                                        <div>
                                          
                                        </div>
                                       
                                        </div>
                                        
                                </div>
                                <div class="panel panel-default" id="production" style="display:none">
                                        <div class="panel-heading ui-draggable-handle">
                                            <h3 class="panel-title text-center"> {{$board_list[0]['board']['name']}} </h3>         
                                        </div>
                                        <div class="panel-body list-group list-group-contacts"> 
                                            @foreach($board_list as $list) 
                                                <?php 
                                                     $is_enable = config_enable($list['board_config'], $list['trello_list_id'], 5); 
                                                ?>
                                                <a href="javascript:void(0)" class="list-group-item {{ $is_enable ? 'active':''}}">
                                                            <span class="enable_report" rel="5">
                                                                <div id="_pro_{{$list['trello_list_id']}}" class="list-group-status status-production  status-{{ $is_enable ? 'online':'offline' }}"></div>
                                                                    <h2 class="contacts-title" style="margin-top: 10px;"> 
                                                                        {{$list['name']}}
                                                                    </h2>
                                                                    <div class="list-group-controls _checkbox">
                                                                    @if($is_enable)   
                                                                        <i  class="fa fa-check production" aria-hidden="true"></i> 
                                                                        <input type="hidden" value="1" name="hook_checked"/>
                                                                    @endif   
                                                                    <input style="display:none;" type="radio" {{( $is_enable  ? 'checked' : '' )}} name="list_id" value="{{$list['trello_list_id']}}">
                                                                </div>
                                                            </span>
                                                            <span class="list-group-controls"  id="pro_{{$list['trello_list_id']}}">
                                                                    @if($is_enable)
                                                                        <button class="btn btn-danger btn-sm delete-hook _production" data = "5" rel="something"> Remove </button>
                                                                    @endif
                                                            </span>
                                                </a>
                                            @endforeach
                                        <div>
                                          
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
