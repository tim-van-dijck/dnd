<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>D&D</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body id="login"
      class="uk-cover-container uk-flex uk-light uk-flex-center uk-flex-middle uk-height-viewport uk-overflow-hidden">
<div class="uk-position-cover uk-overlay-primary"></div>

<div class="uk-width-medium uk-padding-small uk-position-z-index" uk-scrollspy="cls: uk-animation-fade">
    <h1 class="uk-text-center uk-heading-primary">Dungeons<br>&<br>Diaries</h1>
    @if ($errors->any())
        <p class="uk-text-danger uk-align-center">{{ $errors->first() }}</p>
    @endif
    <form class="toggle-class" action="/login" method="post">
        @csrf
        <fieldset class="uk-fieldset">
            <div class="uk-margin-small">
                <div class="uk-inline uk-width-1-1">
                    <input class="uk-input" name="email" required placeholder="Email" type="text"
                           value="{{ old('email') }}">
                    <i class="uk-form-icon uk-form-icon-flip fas fa-user"></i>
                </div>
            </div>
            <div class="uk-margin-small">
                <div class="uk-inline uk-width-1-1">
                    <input class="uk-input" name="password" required placeholder="Password" type="password">
                    <i class="uk-form-icon uk-form-icon-flip fas fa-lock"></i>
                </div>
                <div class="uk-text-right">
                    <a class="uk-link-reset uk-text-small"
                       data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade">Forgot your password?</a>
                </div>
            </div>
            <div class="uk-margin-small">
                <label><input class="uk-checkbox" type="checkbox"> Keep me logged in</label>
            </div>

            <div class="uk-margin-bottom">
                <button type="submit" class="uk-button uk-button-primary uk-width-1-1">LOG IN</button>
            </div>
        </fieldset>
    </form>

    @component('auth.partial.password-reset', ['errors', $errors])
    @endcomponent

    <div>
        <div class="uk-text-center">
            <p class="toggle-class">
                New here? <a class="uk-link-text uk-text-small" href="/register">Create an account</a>.
            </p>
            <a class="uk-link-reset uk-text-small toggle-class"
               data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade" hidden>
                <span data-uk-icon="arrow-left"></span> Back to Login
            </a>
        </div>
    </div>
</div>

<script src="/js/app.js"></script>
</body>
</html>
