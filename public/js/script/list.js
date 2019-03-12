'use strict'
window.addEventListener('load',function(){
    let _cross_token = $('meta[name="_token"]').attr('content'); 
    let data = $("#board_data").attr('rel');
    if(typeof data ==='string'){
       $(".listing_view").css('background','url('+data+')');
    }
    let all_task_id='';
    $('.tasks').attr('id',function(key,val){
        all_task_id+='#'+val+",";
    });
    console.log(all_task_id.slice('0','-1'));
    var tasks = function(){
        $("#add_new_task").on("click",function(){
            var nt = $("#new_task").val();
            if(nt != ''){
                
                var task = '<div class="task-item task-primary">'
                                +'<div class="task-text">'+nt+'</div>'
                                +'<div class="task-footer">'
                                    +'<div class="pull-left"><span class="fa fa-clock-o"></span> now</div>'
                                +'</div>'
                            +'</div>';
                    
                $("#tasks").prepend(task);
            }            
        });
        
        $(all_task_id.slice('0','-1')).sortable({
            items: "> .task-item",
            connectWith: all_task_id.slice('0','-1'),
            handle: ".task-text",            
            receive: function(event, ui) {
                $('.tasks').attr('id',function(key,val){
                    if(this.id == val){
                        ui.item.addClass("task-complete").find(".task-footer > .pull-right").remove();
                    }
                });             
                page_content_onresize();
            }
        }).disableSelection();
        
    }();


    $('input[type=radio][name=list_id]').change(function() {
        var res = this.value.split("~",)
        $.ajax({
            method:'post',
            data:{
               _token:_cross_token,
               list_id:res[0],
               board_id:res[1]
            },
            url: 'http://localhost/naval/tcc/public/register-web-hook',
            beforSend:(()=>{
               // todo
            }),success:()=>{
               swal.stopLoading();
               swal.close();
               swal("Oh yes!", "Data updated successfully", "success");
            },error:(err => {
               swal("Oh noes!", "The AJAX request failed!", "error");
               swal.stopLoading();
            }),complete:(()=>{
               // todo
            })
         });


    });


});