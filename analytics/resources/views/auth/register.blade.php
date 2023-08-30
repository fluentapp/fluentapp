@extends('layouts.auth')
@section('title', 'Register')
@section('content')
<div class="card my-2">
    <form method="POST" action="{{ route('register') }}" class="card-body p-lg-3">
        @csrf
        <h2 class="text-center text-dark mb-5">{{ __('Enter your details') }}</h2>
        <div class="mb-3">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Full Name') }}">
            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email Address') }}">
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="mb-3">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
        </div>
        <div class="text-center"><button type="submit" class="btn btn-color px-5 mb-2 w-100">{{ __('Register') }}</button></div>
        <div class="form-text text-center text-dark">
            {{ __('Already have an account?') }} <a href="{{ route('login') }}" class="text-dark fw-bold"> {{ __('Log in instead.') }}</a>
        </div>
    </form>
</div>
@endsection