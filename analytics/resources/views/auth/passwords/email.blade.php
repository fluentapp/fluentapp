@extends('layouts.auth')
@section('title', 'Password Reset')
@section('content')
<div class="card">
    <div class="card-body p-lg-3">
        <h3 class="text-center text-dark mb-4">{{ __('Forgot Password?') }}</h3>

        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-color px-5 mb-2 w-100">
                        {{ __('Send Password Reset Link') }}
                    </button>
                    <div class="form-text text-dark">
                        {{ __('Not Registered ?') }} <a href="{{ route('register') }}" class="text-dark fw-bold"> {{ __('create_account') }}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection