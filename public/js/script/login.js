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
    console.log(window.location.href);
    let on_done=()=> {
        var oauth_token         = Trello.token();
        var laravel_token       = '{{ csrf_token()}}';
        var send_to_url =   '{{ route(check-trello-login) }}';
        var values      =  'oAuthToken='+oauth_token+'&_token='+laravel_token;
        $.ajax({
            url: window.location.href+'ajax_login',
            type: "post",
            data: values ,
            success: function (response) {
                // you will get response from your php page (what you echo or print)                 

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }

    var  on_error= () => {
    
    }
});