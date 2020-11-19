@extends('layout.register')

@section('content')
    <form action="{{ route('password.update') }}" method="post">
        @csrf
        <fieldset class="uk-fieldset">
            @error('email')
            <div class="uk-alert-danger" uk-alert>
                <p>{{ $errors->first('email') }}</p>
            </div>
            @enderror
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
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
            <div class="uk-margin">
                <button type="submit" class="uk-button uk-button-primary uk-width-1-1">Submit</button>
            </div>
        </fieldset>
    </form>
@stop