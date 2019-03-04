'use strict'
window.addEventListener('load',function(){
    let data = $("#board_data").attr('rel');
    if(typeof data ==='string'){
        data=JSON.parse(data);
        if(data.backgroundImage){
            $(".listing_view").css('background','url('+data.backgroundImage+')');
            $("h3").css('color','white','!important');
        }
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
               
                // if(this.id == "tasks_progreess"){
                //     ui.item.find(".task-footer").append('<div class="pull-right"><span class="fa fa-play"></span> 00:00</div>');
                // }                
                page_content_onresize();
            }
        }).disableSelection();
        
    }();



});