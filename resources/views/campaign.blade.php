<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>D&D</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <script type="text/javascript" src="/js/app.js"></script>
    <script src="https://kit.fontawesome.com/8c4a80dd98.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="app">
        <header-navbar>
            <header id="top-head" class="uk-position-fixed">
                <x-layout.header />
            </header>
        </header-navbar>
        <navigation>

            <aside id="left-col" class="uk-light uk-visible@m">
                <div class="left-logo uk-flex uk-flex-middle">
                    DUNGEONS & DIARIES
                </div>

                <div class="left-nav-wrap">
                    <ul class="uk-nav uk-nav-default" data-uk-nav>
                        <li class="uk-nav-header">CAMPAIGN</li>
                        @for ($i = 0; $i < 5; $i++)
                        <li>
                            <span class="navigation-placeholder">&nbsp;</span>
                        </li>
                        @endfor
                    </ul>
                </div>
                <div class="bar-bottom">
                    <ul class="uk-subnav uk-flex uk-flex-center uk-child-width-1-5" data-uk-grid>
                        @for ($i = 0; $i < 4; $i++)
                            <li>
                                <span class="navigation-placeholder">&nbsp;</span>
                            </li>
                        @endfor
                    </ul>
                </div>
            </aside>
        </navigation>
        <messages></messages>
        <div id="content" uk-height-viewport="expand: true">
            <div class="uk-container uk-container-expand">
                <router-view></router-view>
                <x-layout.footer />
            </div>
        </div>
    </div>
</body>
</html>
