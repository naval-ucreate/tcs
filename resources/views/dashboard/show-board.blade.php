@extends('layouts.dashboard')
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
            <tr class="tr-shadow">
                <td>Lori Lynch</td>
                <td>
                    <span class="block-email">lori@example.com</span>
                </td>
                <td class="desc">Samsung S8 Black</td>
                <td>2018-09-27 02:12</td>
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
        </tbody>
    </table>
</div>
<!-- END DATA TABLE -->
</div>
@stop
