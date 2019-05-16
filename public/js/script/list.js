'use strict';

window.addEventListener('load',function(){
    let base_path = window.location.origin;  
    let _cross_token = $('meta[name="_token"]').attr('content'); 
    let data = $("#board_data").attr('rel');
    let hook_alert=$("input[name='hook_checked']").val();

    if(hook_alert==undefined){
      $('.hook-alert').show();
    }

   if(typeof data ==='string'){
       $(".listing_view").css('background','url('+data+')');
   }

   
   $(".add_bug").on('click', function() {
      if($(this).children().find('input').prop('checked')) {
         $(this).children().find('input').prop('checked', false);
         return false;
      }
      $(this).children().find('input').prop('checked', true);
   }); 
    
   $('.config').on('click', function(){
      let active_tab = $(this).children('a').attr('rel');
      let deactive_tab = $(this).siblings().children('a').attr('rel');
      $("#"+active_tab).show();
      $("#"+deactive_tab).hide();
   });
   
   $('.update_bug').on('click', function(){
      $(".loading_loader").show();
      let borad_id = window.location.pathname.split('/');
      borad_id = borad_id[borad_id.length-1];
      let list_ids = [];
      $("input[name='list_id[]']:checked").each( function () {
         list_ids.push($(this).val());
      });

      $.ajax({
         method:'put',
         data:{
            _token:_cross_token,
            list_ids: list_ids.toString(),
         },
         url:base_path+'/update_bug_list/'+borad_id,
         beforsend:()=>{

         },success:(data)=>{
            swal("Success Information", "Inforamtion Updated", "success");
         },error:(err => {
            swal("Oh noes!", "Something went wrong", "error");
         }),complete:(()=>{
            $(".loading_loader").hide();
         })
      });

   });

   $(document.body).on('click','.delete-hook', deleteHook);

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
                     method:'put',
                     data:{
                        _token:_cross_token,
                        status:false
                     },
                     url:base_path+'/disable_check_list/'+data[0],
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
        let res = $(this).children().find('input').val().split("~",)
        let tick = $(this).children('._checkbox');
        $.ajax({
            method:'put',
            data:{
               _token:_cross_token,
               board_id:res[1],
               status:true
            },
            url:base_path+'/enable_check_list/'+res[0],
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