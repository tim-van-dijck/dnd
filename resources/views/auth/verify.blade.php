@extends('layout.app')

@section('content')
    <h1 class="uk-heading-small">{{ __('Verify Your Email Address') }}</h1>
    <div class="uk-section uk-section-default uk-padding">
        @if (session('resent'))
            <div class="uk-alert uk-alert-success" uk-alert>
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif
        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('Didn\'t receive an email') }}?
        <form class="uk-margin-top" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="uk-button uk-button-primary">{{ __('request another') }}</button>
        </form>
    </div>
@stop