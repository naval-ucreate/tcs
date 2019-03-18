'use strict';

window.addEventListener('load',function(){
    let _cross_token = $('meta[name="_token"]').attr('content'); 
    let data = $("#board_data").attr('rel');

    if(typeof data ==='string'){
       $(".listing_view").css('background','url('+data+')');
    }

    // for delete the hook 
    
    $(document.body).on('click','.delete-hook',deleteHook);

    function deleteHook(){
      let _this=$("input[name='list_id']:checked");
      let data =_this.val();
      if(data==undefined){
         swal("No Webhook register",{
            icon: "error",
         });
         return false;
      }
      data = data.split("~",);
      swal({
         title: "Are you sure Want to delete the hook?",
         icon: "warning",
         buttons: true,
         dangerMode: true,
       })
       .then((willDelete) => {
         if (willDelete) {
            $.ajax({
               method:'post',
               data:{
                  _token:_cross_token,
                  list_id:data[0],
                  board_id:data[1]
               },
               url:$("#delete_hook").text(),
               beforSend:()=>{
                  // todo
                  
               },success:(data)=>{
                  if(data){
                     _this.prop('checked',false);
                     swal("", "Done", "success");
                     return ;
                  }
                  swal("Oh no", "Something want wrong", "error");
               },error:(err => {
                   console.log(err);
                  swal("Oh noes!", "The AJAX request failed!", "error");
               }),complete:(()=>{
                  // todo
               })
            });
         } else {
           swal("Request cancel",'error');
         }
       });
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