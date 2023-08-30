@extends('layouts.auth')
@section('title', 'Login')
@section('content')

<div class="card">
    <form method="POST" action="{{ route('login') }}" class="card-body p-lg-3">
        @csrf
        <h2 class="text-center text-dark mb-5">{{ __('Enter your email and password') }}</h2>
        <div class="mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email Address') }}" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="mb-3">
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class=" mb-3">
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
        </div>
        <div class="text-center"><button type="submit" class="btn btn-color px-5 mb-2 w-100">{{ __('Login') }}</button></div>
        <div class="text-center text-dark">
            @if (Route::has('password.request'))
            <a class="btn btn-link text-dark" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
            @endif
        </div>
        <div class="form-text text-center text-dark">
            {{ __('Not Registered ?') }} <a href="{{ route('register') }}" class="text-dark fw-bold"> {{ __('create_account') }}</a>
        </div>
    </form>
</div>

@endsection