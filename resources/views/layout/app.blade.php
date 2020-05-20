<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>D&D</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8c4a80dd98.js" crossorigin="anonymous"></script>
</head>
<body>
    <header class="uk-position-fixed uk-width-1-1">
        <div class="uk-container uk-container-expand uk-background-primary">
            <nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
                <div class="uk-navbar-left">
                    <ul class="uk-navbar-nav">
                        <li><a href="/" class="uk-navbar-item uk-navbar-logo">KANKA HARD GAAN</a></li>
                    </ul>
                </div>
                <div class="uk-navbar-right">
                    <ul class="uk-navbar-nav">
                        <li>
                            <a href="/campaigns" class="uk-navbar-item">My Campaigns</a>
                        </li>
                        <li>
                            <a href="/profile" class="uk-navbar-item">My Profile</a>
                        </li>
                        <li>
                            <form action="/logout" method="post">
                                @csrf
                                <button type="submit" class="uk-navbar-item uk-button uk-button-link uk-link-text">
                                    <i class="fas fa-sign-out-alt fa-fw"></i>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <div id="content" class="uk-width-1-1" uk-height-viewport="expand: true">
        <div class="uk-container uk-container-expand">
            @yield('content')
            <footer class="uk-section uk-section-small uk-text-center">
                <hr>
                <p class="uk-text-small uk-text-center">
                    &copy; Copyright {{ date('Y') }} - <a href="https://timvandijck.com">Created by ne spast</a>
                </p>
            </footer>
        </div>
    </div>
    @yield('js')
    <script src="/js/app.js"></script>
</body>
</html>
