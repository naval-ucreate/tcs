'use strict'
window.addEventListener('load',function(){
    let data = $("#board_data").attr('rel');
    if(typeof data ==='string'){
        data=JSON.parse(data);
        if(data.backgroundImage){
            $("#_contest_").css('background','url('+data.backgroundImage+')');
        }
    }




});