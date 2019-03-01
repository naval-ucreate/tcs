@extends('layouts.dashboard')
@section('pageTitle', 'Lists')
@section('content')
    <div id="board_data" rel='{{json_encode($board)}}'> </div>
@endsection
<script src="{{url('js/script/list.js')}}"></script>
