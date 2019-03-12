'use strict'
window.addEventListener('load',function(){
    let _cross_token = $('meta[name="_token"]').attr('content'); 
    let data = $("#board_data").attr('rel');

    if(typeof data ==='string'){
       $(".listing_view").css('background','url('+data+')');
    }

    $('input[type=radio][name=list_id]').change(function() {
        var res = this.value.split("~",)
        console.log(location.host);
        $.ajax({
            method:'post',
            data:{
               _token:_cross_token,
               list_id:res[0],
               board_id:res[1]
            },
            url:"http://127.0.0.1/pankajvashisht/tcl/public/register-web-hook",
            beforSend:()=>{
               // todo
               
            },success:()=>{
               swal("Oh yes!", "webhook triger", "success");
            },error:(err => {
                console.log(err);
               swal("Oh noes!", "The AJAX request failed!", "error");
            }),complete:(()=>{
               // todo
            })
         });

    });


});