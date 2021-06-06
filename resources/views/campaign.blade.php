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
                <div class="uk-container uk-container-expand uk-background-primary">
                    <nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
                        <div class="uk-navbar-left">
                            <div class="uk-navbar-item uk-visible@s">
                            </div>
                        </div>
                        <div class="uk-navbar-right">
                            <ul class="uk-navbar-nav">
                                <li><a href="/campaigns" class="uk-navbar-item">My Campaigns</a></li>
                                <li><a href="/profile" class="uk-navbar-item">My Profile</a></li>
                                <li>
                                    <a href="/logout" title="Sign out" class="uk-navbar-item"
                                       @click.prevent="$store.dispatch('logout')">
                                        <i class="fas fa-sign-out-alt fa-fw"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
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
                @component('partial.footer')
                @endcomponent
            </div>
        </div>
    </div>
</body>
</html>
