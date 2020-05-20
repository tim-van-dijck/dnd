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
            <form action="{{ $token ? route('register-invite', ['token' => $token]) : route('register') }}" method="post">
                @csrf
                <fieldset class="uk-fieldset">
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <input class="uk-input {{ $errors->has('name') ? 'uk-form-danger' : '' }}"
                                   name="name" placeholder="Name" type="text" value="{{ old('name') }}">
                            <i class="uk-form-icon uk-form-icon-flip fas fa-user"></i>
                        </div>
                        @error('name')
                            <span class="uk-text-danger uk-text-small uk-align-right">
                                {{ $errors->first('name') }}
                            </span>
                        @enderror
                    </div>
                    @if (!$token)
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <input class="uk-input {{ $errors->has('email') ? 'uk-form-danger' : '' }}"
                                   name="email" placeholder="Email" type="email" value="{{ old('email') }}">
                            <i class="uk-form-icon uk-form-icon-flip fas fa-envelope"></i>
                        </div>
                        @error('email')
                            <span class="uk-text-danger uk-text-small uk-align-right">
                                {{ $errors->first('email') }}
                            </span>
                        @enderror
                    </div>
                    @endif
                    <div class="uk-alert-primary" uk-alert>
                        <p>Passwords must be at least 16 characters long and contain at least <b>one lowercase, one uppercase and one numeric character</b>.</p>
                    </div>
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <input class="uk-input {{ $errors->has('password') ? 'uk-form-danger' : '' }}"
                                   name="password" placeholder="Password" type="password">
                            <i class="uk-form-icon uk-form-icon-flip fas fa-lock"></i>
                        </div>
                        @error('password')
                            <span class="uk-text-danger uk-text-small uk-align-right">
                                {{  $errors->first('password') }}
                            </span>
                        @enderror
                    </div>
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <input class="uk-input {{ $errors->has('password') ? 'uk-form-danger' : '' }}"
                                   name="password_confirmation" placeholder="Confirm password" type="password">
                            <i class="uk-form-icon uk-form-icon-flip fas fa-lock"></i>
                        </div>
                        @error('password_confirmation')
                            <span class="uk-text-danger uk-text-small uk-align-right">
                                {{ $errors->first('password_confirmation') }}
                            </span>
                        @enderror
                    </div>

                    <p class="uk-text-small uk-text-center">Your data won't be used for anything malicious.<br>That's a <a class="uk-link-text" target="_blank" href="https://www.youtube.com/watch?v=1PWTcIU6s9M">Pinky Promise</a></p>

                    <div class="uk-margin">
                        <button type="submit" class="uk-button uk-button-primary uk-width-1-1">Register</button>
                    </div>
                    <p class="uk-text-small uk-text-center">Already have an account? <a href="{{ route('login') }}">Sign in!</a></p>
                </fieldset>
            </form>
        </div>
    </div>

    <script src="/js/app.js"></script>
</body>
</html>