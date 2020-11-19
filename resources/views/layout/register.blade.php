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
<body id="register" class="uk-cover-container uk-flex uk-flex-center uk-flex-middle uk-height-viewport uk-overflow-hidden">
<div class="uk-position-cover uk-overlay-primary"></div>

<div class="uk-width-large uk-position-z-index" uk-scrollspy="cls: uk-animation-fade">
    <div class="uk-section uk-section-default uk-padding-small">
        @yield('content')
    </div>
</div>

<script src="/js/app.js"></script>
</body>
</html>