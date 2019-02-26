<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Trello Controll CheckList: Login</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
        <!--include whatever version of jquery you want to use first -->
        <script src="https://api.trello.com/1/client.js?key=5b60d3f32d9fadef119dfaf96af008ba" type="text/javascript"></script>
        <script>
        // call this whenever you want to make sure Trello is authenticated, and get a key. 
        // I don't call it until the user needs to push something to Trello,
        // but you could call it in document.ready if that made more sense in your case.
        // help url ::: https://developers.trello.com/docs/getting-started-with-clientjs
        function AuthenticateTrello() {
            Trello.authorize({
            name: "Trello Api",
            type: "popup",
            interactive: true,
            expiration: "never",
            success: function () { onAuthorizeSuccessful(); },
            error: function () { onFailedAuthorization(); },
            scope: { write: true, read: true },
            });
        }

        function onAuthorizeSuccessful() {
        var oauth_token         = Trello.token();
        var laravel_token       = '{{ csrf_token()}}';
        console.log(oauth_token);
        alert(oauth_token)
        // whatever you want to do with your token. 
        // if you can do everything client-side, there are other wrapper functions
        // so you never need to use the token directly if you don't want to.
        var send_to_url =   '{{ route('check-trello-login') }}';
        var values      =   'oAuthToken='+oauth_token+'&_token='+laravel_token;
        $.ajax({
            url: send_to_url,
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

        function onFailedAuthorization() {
        // whatever
        }
        </script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body onLoad="AuthenticateTrello()">
        <div class="flex-center position-ref full-height">            
            <div class="content">
                <div class="title m-b-md">
                   <a href="">Click here to login with trello</a>
                </div>
            </div>
        </div>
    </body>
</html>
