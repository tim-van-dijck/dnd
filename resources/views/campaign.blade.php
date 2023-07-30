<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="token" content="{{Auth::user()->api_token}}">
    <meta name="campaign-id" content="{{$campaignId}}">

    <title>D&D</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <script type="text/javascript" src="/js/campaign.js"></script>
</head>
<body>
<div id="app">
</div>
</body>
</html>
