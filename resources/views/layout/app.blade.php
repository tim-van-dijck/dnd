<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>D&D</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8c4a80dd98.js" crossorigin="anonymous"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <header class="uk-position-fixed uk-width-1-1">
        <x-layout.header />
    </header>
    <div id="content" class="uk-width-1-1" uk-height-viewport="expand: true">
        <div class="uk-container uk-container-expand">
            @yield('content')
            <x-layout.footer />
        </div>
    </div>
    @yield('js')
</body>
</html>
