<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <style>
            html {
                color: #149414;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                margin: 0;
            }

            body {
                margin:0;
                box-sizing:border-box;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #111111;
                background: url(/assets/htp.jpeg) 100px 100px;
                background-size: 200px 200px;
                animation: animation 100s linear infinite forwards;
            }

            @keyframes animation {
                100% {
                    background-position: -2000px +2000px;
                }
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

            .code {
                border-right: 2px solid;
                font-size: 26px;
                padding: 0 15px 0 15px;
                text-align: center;
            }

            .message {
                font-size: 18px;
                text-align: center;
            }

            .container{
                background-color:#000;
                display:flex;
                flex-direction:row;
                align-items:center;
                justify-contents:center;
                padding:10px;
                border-radius: 5px;
                border: solid 2px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="container">
                <div class="code">
                    @yield('code')
                </div>

                <div class="message" style="padding: 10px;">
                    @yield('message')
                </div>
            </div>
        </div>
    </body>
</html>
