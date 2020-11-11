@extends('layout.app')

@section('content')
    <h1>Manage your profile</h1>
    <form class="uk-section uk-section-default" method="post" action="{{ route('profile.save') }}">
        <div class="uk-container padded">
            <form id="campaign-form" class="uk-form-stacked">
                @csrf
                <div class="uk-margin">
                    <label for="name" class="uk-form-label {{ $errors->has('name') ? 'uk-text-danger': '' }}">Name*</label>
                    <input id="name" name="name" type="text" class="uk-input {{ $errors->has('name') ? 'uk-form-danger': '' }}"
                           value="{{ old('name', $user->name) }}">
                </div>
                <div class="uk-margin">
                    <label for="email" class="uk-form-label {{ $errors->has('email') ? 'uk-text-danger': '' }}">Email*</label>
                    <input id="email" name="email" type="text" class="uk-input {{ $errors->has('email') ? 'uk-form-danger': '' }}"
                           value="{{ old('email', $user->email) }}">
                </div>
                <h2>Change password</h2>
                <div class="uk-margin">
                    <label for="old_password" class="uk-form-label {{ $errors->has('old_password') ? 'uk-text-danger': '' }}">Old password</label>
                    <input id="old_password" name="old_password" type="password" class="uk-input {{ $errors->has('old_password') ? 'uk-form-danger': '' }}">
                </div>
                <div class="uk-margin">
                    <label for="password" class="uk-form-label {{ $errors->has('password') ? 'uk-text-danger': '' }}">Password</label>
                    <input id="password" name="password" type="password" class="uk-input {{ $errors->has('password') ? 'uk-form-danger': '' }}">
                </div>
                <div class="uk-margin">
                    <label for="password_confirmation" class="uk-form-label {{ $errors->has('password_confirmation') ? 'uk-text-danger': '' }}">
                        Confirm password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           class="uk-input {{ $errors->has('password_confirmation') ? 'uk-form-danger': '' }}">
                </div>
                <p class="uk-margin">
                    <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                    <a class="uk-button uk-button-danger" href="{{ route('campaigns.index') }}">
                        Cancel
                    </a>
                </p>
            </form>
        </div>
    </form>
@stop