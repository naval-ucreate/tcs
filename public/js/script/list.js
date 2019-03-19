'use strict';

window.addEventListener('load',function(){
    let _cross_token = $('meta[name="_token"]').attr('content'); 
    let data = $("#board_data").attr('rel');
    let hook_alert=$("input[name='hook_checked']").val();
    if(hook_alert==undefined){
      $('.hook-alert').show();
    }

    if(typeof data ==='string'){
       $(".listing_view").css('background','url('+data+')');
    }

    // for delete the hook 
    
    $(document.body).on('click','.delete-hook',deleteHook);

    function deleteHook(e){
            e.preventDefault();
            let _this=$("input[name='list_id']:checked");
            let data =_this.val();
            if(data==undefined){
               swal("No Webhook register",{
                  icon: "error",
               });
               return false;
            }
            $(".list-group-item").css('pointer-events','none');
            data = data.split("~",);
            swal({
               title: "Are you sure want to delete the hook?",
               icon: "warning",
               buttons: true,
               dangerMode: true,
            })
            .then((willDelete) => {
               if (willDelete) {
                  $(".loading_loader").show();
                  $.ajax({
                     method:'post',
                     data:{
                        _token:_cross_token,
                        list_id:data[0],
                        board_id:data[1]
                     },
                     url:$("#delete_hook").text(),
                     beforsend:()=>{
                     
                        
                     },success:(data)=>{
                        if(data){
                           _this.prop('checked',false);
                           $(".list-group-status").removeClass('status-online');
                           $(".list-group-status").addClass('status-offline');
                           $(".fa-check").remove();
                           $(".list-group-item").removeClass('active');
                           $(".hook-alert").fadeIn('slow');
                           $(this).remove();
                           return ;
                        }
                        swal("Oh no", "Something want wrong", "error");
                     },error:(err => {
                        console.log(err);
                        swal("Oh noes!", "The AJAX request failed!", "error");
                     }),complete:(()=>{
                        $(".loading_loader").hide();
                        $(".list-group-item").css('pointer-events','');
                     })
                  });
               } else {
               swal("Request cancel",'error');
               }
            });
    }

 


    $(".add_hook").on('click',function(){
       $(".loading_loader").show();
       $(".list-group-item").css('pointer-events','none');
        var res = $(this).children().find('input').val().split("~",)
        var tick = $(this).children('._checkbox');
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
              
               
            },success:()=>{
               $('.hook-alert').fadeOut('slow');
               $(".delete-hook").remove();
               $(".fa-check").remove();
               $(".list-group-item").removeClass('active');
               $(this).addClass('active');
               $(this).children().find('input').prop('checked',true);
               tick.append('<i class="fa fa-check" aria-hidden="true"></i> ');
               $(".list-group-status").removeClass('status-online');
               $(".list-group-status").addClass('status-offline');
               $("#_"+res[0]).removeClass('status-offline');
               $("#_"+res[0]).addClass('status-online');
               $("#"+res[0]).append(' <button class="btn btn-danger btn-sm delete-hook" rel="something"> Remove Hook </button>');
            },error:(err => {
                console.log(err);
               swal("Oh noes!", "The AJAX request failed!", "error");
            }),complete:(()=>{
               $(".loading_loader").hide();
               $(".list-group-item").css('pointer-events','');
            })
         });
    });
});