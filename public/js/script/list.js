'use strict'
window.addEventListener('load',function(){
    let _cross_token = $('meta[name="_token"]').attr('content'); 
    let data = $("#board_data").attr('rel');

    if(typeof data ==='string'){
       $(".listing_view").css('background','url('+data+')');
    }

    $('input[type=radio][name=list_id]').change(function() {
        var res = this.value.split("~",)
        var s_url =  $('#reg_url').text();
        console.log(s_url);
        $.ajax({
            method:'post',
            data:{
               _token:_cross_token,
               list_id:res[0],
               board_id:res[1]
            },
            url:s_url,
            beforSend:()=>{
               // todo
               
            },success:()=>{
               swal("", "Done", "success");
            },error:(err => {
                console.log(err);
               swal("Oh noes!", "The AJAX request failed!", "error");
            }),complete:(()=>{
               // todo
            })
         });

    });


});