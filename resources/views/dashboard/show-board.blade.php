@extends('layouts.dashboard')
@section('pageTitle', 'Boards')
@section('content')
<div class="page-title">                    
<h2><span class="fa fa-arrow-circle-o-left"></span>Boards</h2>
</div>
<div class="page-content-wrap">                
    <div class="row">
        <div class="col-md-12">
        <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title">Striped rows</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Board Id</th>
                        <th>Board Name</th>
                        <th>Board Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @forelse ($userBoardsData as $userBoardsVal)            
                    <tr class="tr-shadow">
                        <td>{{ $count++ }}</td>
                        <td>{{ $userBoardsVal->trello_board_id }}</td>
                        <td>{{ $userBoardsVal->name }}</td>
                        <td>{{ $userBoardsVal->name }}</td>
                        <td>
                            <div class="table-data-feature">
                                <button class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Send">
                                    <i class="zmdi zmdi-mail-send"></i>
                                </button>
                                <button class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                    <i class="zmdi zmdi-edit"></i>
                                </button>
                                <button class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                    <i class="zmdi zmdi-delete"></i>
                                </button>
                                <button class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="More">
                                    <i class="zmdi zmdi-more"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="spacer"></tr> 
                    @empty
                    <p>No users</p>
                    @endforelse           
                </tbody>
            </table>
            </div>    
        </div>
</div>
</div>
</div>
@stop