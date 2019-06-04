@extends('layouts.dashboard')
@section('pageTitle', $baord_info->name)
@section('content')
    <?php
         $backgroud_image = $baord_info->background_image;
    ?>
     
    <List></List>         
@endsection

<script>
    window.addEventListener("load", function() {
        setTimeout(() => {
                $(".listing_view").css("background", "url( <?= $backgroud_image ?> )");
        }, 1000);
    });
</script>
