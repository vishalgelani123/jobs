@extends('auth.layouts.app')
@section('title')
    Reset Password
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="{{route('login')}}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{asset('assets/images/logo.png')}}">
                                </span>
                            </a>
                        </div>
                        <h4 class="mb-1 pt-2">Welcome to {{config('app.name')}}!</h4>
                        <p class="mb-4">Reset Password</p>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="email"
                                           class=" col-form-label text-md-end">{{ __('Email Address') }}</label>

                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email"
                                           placeholder="Enter your email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Send Password Reset Link
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
