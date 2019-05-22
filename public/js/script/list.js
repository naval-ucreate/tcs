'use strict';

window.addEventListener('load',function(){
    let base_path = window.location.origin;  
    let _cross_token = $('meta[name="_token"]').attr('content'); 
    let data = $("#board_data").attr('rel');
    let board_id = window.location.pathname.split('/');
    board_id = board_id[board_id.length-1];
    let hook_alert=$("input[name='hook_checked']").val();
    
    let classs = {
      1 : 'checklist',
      3 : 'qa',
      5 : 'production'
   };

    if(hook_alert == undefined){
      $('.hook-alert').show();
    }

   if(typeof data ==='string'){
       $(".listing_view").css('background','url('+data+')');
   }

   
   $(".add_config").on('click', function() {
      if($(this).children().find('input').prop('checked')) {
         $(this).children().find('input').prop('checked', false);
         return false;
      }
      $(this).children().find('input').prop('checked', true);
   }); 
    
   $('.config').on('click', function() {
      let active_tab = $(this).children('a').attr('rel');
      $(this).siblings('li').filter( function() {
         $("#"+$(this).children('a').attr('rel')).hide();
      });
      $("#"+active_tab).show();
   });
   
   $('.update_config').on('click', function() {
      $(".loading_loader").show();
      let borad_id = window.location.pathname.split('/');
      borad_id = borad_id[borad_id.length-1];
      let list_ids = [];
      let type = $(this).attr('data');
      $(this).parents().find("input[name='list_id[]']:checked").each( function () {
         list_ids.push($(this).val());
      });
      $.ajax({
         method:'put',
         data:{
            _token:_cross_token,
            list_ids: list_ids.toString(),
            type: type
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

   $(document.body).on('click','.delete-hook', checkListRemove);

   function checkListRemove(e) {
            e.preventDefault();
            let list_id = $(this).parents().children().find('input').val();
            let type = $(this).attr('data');
            if(list_id == undefined || list_id==''){
               swal("No Webhook register",{
                  icon: "error",
               });
               return false;
            }

            $(".list-group-item").css('pointer-events','none');
            
            swal({
               title: "Are you sure want to delete the hook?",
               icon: "warning",
               buttons: true,
               dangerMode: true,
            }).then(( willDelete ) => {
               if (willDelete) {
                  $(".loading_loader").show();
                  $.ajax({
                     method:'put',
                     data:{
                        _token:_cross_token,
                        board_id: board_id,
                        type : type,
                        status:false
                     },
                     url:base_path+'/disable_config/'+list_id,
                     success:(data) => {
                        if(data){
                           $(this).parents().children().find('input').prop('checked',false);
                           removeFaClass(type);
                           $(this).parents().children().removeClass('active');
                           $(".hook-alert").fadeIn('slow');
                           $(this).remove();
                           return ;
                        }
                        swal("Oh no", "Something want wrong", "error");
                     },error:(err => {

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

 


    $(".enable_report").on('click',function() {
       $(".loading_loader").show();
       $(".list-group-item").css('pointer-events','none');
        let list_id = $(this).children().find('input').val();
        let tick = $(this).children('._checkbox');
        let type = $(this).attr('rel');
        $.ajax({
            method:'put',
            data:{
               _token : _cross_token,
               board_id : board_id,
               type : type,
               status:true
            },
            url:base_path+'/config_enable/'+ list_id,
            beforSend:()=> {
 
            },success:()=> {
               $('.hook-alert').fadeOut('slow');
               removeFaClass(type);
               $(this).parents().parents().find('a').removeClass('active');
               $(this).addClass('active');
               $(this).children().find('input').prop('checked',true);
               tick.append('<i class="fa fa-check '+classs[type]+'" aria-hidden="true"></i> ');
               addRemove(type, list_id);
            },error:(err => {
                console.log(err);
               swal("Oh noes!", "The AJAX request failed!", "error");
            }),complete:(()=> {
               $(".loading_loader").hide();
               $(".list-group-item").css('pointer-events','');
            })
         });
    });

    let addRemove = (type, list_id) => {
      let ids = {
         1 : '',
         3 : 'qa_',
         5 : 'pro_'
      };
      $("#_" + ids[type] + list_id).removeClass('status-offline');
      $("#_" + ids[type] + list_id).addClass('status-online');
      $("#" + ids[type] + list_id).append(' <button class="btn btn-danger btn-sm delete-hook _'+classs[type]+'" data = "'+type+'" rel="something"> Remove </button> ');
    }

    let removeFaClass = (type) => {
      $('.'+classs[type]).remove();
      $('._'+classs[type]).remove();
      $('.status-' + classs[type]).removeClass('status-online');
      $('.status-' + classs[type]).addClass('status-offline');
    }

});