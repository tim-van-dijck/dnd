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
    <div id="app">
        <header id="top-head" class="uk-position-fixed">
            <div class="uk-container uk-container-expand uk-background-primary">
                <nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
                    <div class="uk-navbar-left">
                        <div class="uk-navbar-item uk-visible@s">
                            <form action="dashboard.html" class="uk-search uk-search-default">
                                <span data-uk-search-icon></span>
                                <input class="uk-search-input search-field" type="search" placeholder="Search">
                            </form>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <navigation></navigation>
        <div id="content" uk-height-viewport="expand: true">
            <div class="uk-container uk-container-expand">
                <router-view></router-view>
                <footer class="uk-section uk-section-small uk-text-center">
                    <hr>
                    <p class="uk-text-small uk-text-center">
                        Copyright {{ date('Y') }} - <a href="https://timvandijck.com">Created by ne spast</a>
                    </p>
                </footer>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/js/app.js"></script>
</body>
</html>
