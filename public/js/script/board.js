'use strict'

window.addEventListener('load',function(){
   let _cross_token = $('meta[name="_token"]').attr('content');  
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
