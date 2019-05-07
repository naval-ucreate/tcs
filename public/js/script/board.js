'use strict'

window.addEventListener('load',function(){
   let _cross_token = $('meta[name="_token"]').attr('content');
   let base_path = window.location.origin; 
   
   $(document.body).on('click','._register',function(){
      let board_id = $(this).attr('data');
      let status = $(this).attr('status');
      let _this = $(this);
      if(status){
         swal({
            title: "Are you sure want to disable the report?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
         }) .then((willDelete) => {
               if (willDelete) {
                  $(this).find('.icon_show').show();
                  remove_hook(status, board_id).then(() => {
                     $(this).find('.icon_show').hide();
                     _this.removeClass('btn-danger');
                     _this.addClass('btn-info');
                     _this.attr('status', null);
                     _this.html('Enabled Report <i class="fa fa-spinner fa-spin icon_show" style="display:none;" aria-hidden="true"></i>');
                  }).catch( () => {
                     $(this).find('.icon_show').hide();
                  });
               }
            });   
         return false;
      }

      $(this).find('.icon_show').show();
      reqgister(status, board_id).then(() => {
         $(this).find('.icon_show').hide();
         _this.removeClass('btn-info');
         _this.addClass('btn-danger');
         _this.attr('status', true);
         _this.html('Disable Report <i class="fa fa-spinner fa-spin icon_show" style="display:none;" aria-hidden="true"></i>');
      }).catch( () => {
         $(this).find('.icon_show').hide();
      });
    });


    let reqgister = (...data) => {
      const [status, board_id] = data;
      return new Promise((Response, Reject) => {
         $.ajax({
            method:'put',
            data:{
               _token:_cross_token,
               status:status,
            },
            url: base_path+'/enable_report/'+board_id,
            beforSend:(()=>{
               // todo
            }),success:()=>{
               swal("sucess", "Report Enabled", "success");
               Response(true);
            },error:(err => {
               Reject(true);
               swal("Oh noes!", "Something went wrong", "error");
            }),complete:(()=>{
               // todo
            })
         });
      });
    }

    let remove_hook = (...data) => { 
      const [status, board_id] = data;
      return new Promise((Response, Reject) => {
         $.ajax({
            method:'delete',
            data:{
               _token:_cross_token,
               status: status
            },
            url: base_path+'/disable_hook/'+board_id,
            beforSend:(()=>{
               // todo
            }),success:()=>{
               swal("sucess", "Report Disabled", "success");
               Response(true);
            },error:(err => {
               swal("Error", "Something went wrong", "error");
               Reject(true);
            }),complete:(()=>{
               // todo
            })
         });
      });
    }

    $(document.body).on('click','.delete_data',function(){
        let id    =  $(this).attr('rel');
        swal({
            text: 'For Delete this boards write Down Delete ?',
            content: "input",
            button: {
               text: "Delete",
               placehoder:"Write Delete to Delete this",
               color:"danger",
               closeModal: false,
            },
            })
            .then(name => {
               if (!name) throw null;
               if(name!='Delete') throw null;
               return true;
            })
            .then(() => {
               $.ajax({
                  method:'delete',
                  data:{
                     _token:_cross_token
                  },
                  url: 'ajax_delete/'+id,
                  beforSend:(()=>{
                     // todo
                  }),success:()=>{
                     swal.stopLoading();
                     swal.close();
                     swal("Success", "Data Delete successfully", "success");
                     $(this).parent().parent().parent().fadeOut('slow');
                  },error:(err => {
                     swal("Oh noes!", "The AJAX request failed!", "error");
                     swal.stopLoading();
                  }),complete:(()=>{
                     // todo
                  })
               });
            }).catch(()=>{
               swal.stopLoading();
               swal.close();
            })
    });
    $(document.body).on('click','.update_data',function(){
       console.log("test");
    });   
});
