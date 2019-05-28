@extends('layouts.dashboard')
@section('pageTitle', 'Activities')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">                                    
                    <div class="row stacked">
                        <div class="col-md-6">
                            <div class="input-group push-down-10">
                                <span class="input-group-addon"><span class="fa fa-search"></span></span>
                                <input type="text" class="form-control" placeholder="Keywords..." value="Card Name">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary">Search</button>
                                </div>
                            </div>                                                                
                            
                            <span class="line-height-30">Search Results for <strong></strong> ( results)</span>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button class="btn btn-default active"><span class="fa fa-user"></span></button>
                                    <button class="btn btn-default"><span class="fa fa-globe"></span></button>                                        
                                </div>
                                <button class="btn btn-default"><span class="fa fa-cog"></span></button>
                            </div>
                        </div>
                        <div class="col-md-6">                                         
                        </div>
                    </div>
                </div>                                                                
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="activity_profile">
                    <!-- <img src="https://cdn.pixabay.com/photo/2015/03/04/22/35/head-659652_960_720.png" />
                    <p> Pankaj Vashisht</p> -->
                </div>
            </div>
            <div class="col-md-4">
                <h3>To-do List</h3>
                <div class="tasks ui-sortable">
                    <div class="task-item task-warning">                                    
                        <div class="task-text ui-sortable-handle">Donec lacus lacus, iaculis nec pharetra id, congue ut tortor. Donec tincidunt luctus metus eget rhoncus.</div>
                            <div class="task-footer">
                                <div class="pull-left"><span class="fa fa-clock-o"></span> 1day ago</div>
                            </div>                                    
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="move_arrow">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </div>
            </div>
            <div class="col-md-4">
                <h3>some list</h3>
                <div class="tasks ui-sortable">
                    <div class="task-item task-success">                                    
                        <div class="task-text ui-sortable-handle">Donec lacus lacus, iaculis nec pharetra id, congue ut tortor. Donec tincidunt luctus metus eget rhoncus.</div>
                            <div class="task-footer">
                                <div class="pull-left"><span class="fa fa-clock-o"></span> 1day ago</div>
                            </div>                                    
                    </div>
                </div>    
            </div>
        </div>

    
@endsection
