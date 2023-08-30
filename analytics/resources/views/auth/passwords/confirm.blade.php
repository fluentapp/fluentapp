@extends('layouts.auth')
@section('title', 'Confirm Password')
@section('content')
<div class="card">
    <div class="card-body p-lg-3">
        <h2 class="text-center text-dark mb-1">{{ __('Confirm Password') }}</h2>

        {{ __('Please confirm your password before continuing.') }}
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-color px-5 mb-2 w-100">
                        {{ __('Confirm Password') }}
                    </button>
                    @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection