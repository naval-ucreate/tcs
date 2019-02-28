'use strict'
window.addEventListener('load',function(){
    $(document.body).on('click','.login',function(){
        login_trello();
    });
    var login_trello = () => {
        Trello.authorize({
        name: "Trello Api",
        type: "popup",
        interactive: true,
        expiration: "never",
        success: () => on_done() ,
        error:  () =>  on_error(),
        scope: { 
                write: true, read: true 
               },
        });
    }
    let on_done = () => {
        let oauth_token         = Trello.token();
        let _cross_token = $('meta[name="_token"]').attr('content');
        $.ajax({
            url: window.location.href+'ajax_login',
            type: "post",
            data: {
                trello_token: oauth_token,
                _token :_cross_token
            } ,
            success:  (login_data) => {
                if(login_data.hasOwnProperty('success') && login_data.success==true){
                    window.location.href="dashboard";
                }
            },
            error: (jqXHR, textStatus, errorThrown) =>  {
    
            }
        });

    }

    let on_error= () => {
    
    }
});