<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>D&D</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <script>window.App = {!! json_encode(['api_token' => Auth::user()->api_token]) !!}</script>
    <script type="text/javascript" src="/js/admin.js"></script>
    <script src="https://kit.fontawesome.com/8c4a80dd98.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="admin-app"></div>
</body>
</html>
