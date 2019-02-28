@extends('layouts.dashboard')
@section('pageTitle', 'Boards')
@section('content')
<div class="col-md-12">
<!-- DATA TABLE -->
<h3 class="title-5 m-b-35">Boards</h3>                             
<div class="table-responsive table-responsive-data2">
    <table class="table table-data2">
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
<!-- END DATA TABLE -->
</div>
@stop
