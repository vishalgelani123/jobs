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
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="{{route('login')}}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{asset('assets/images/logo.png')}}">
                                </span>
                            </a>
                        </div>
                        <h4 class="mb-1 pt-2">Welcome to {{config('app.name')}}!</h4>
                        <p class="mb-4">Reset Password</p>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row mb-3">
                                <div class="col-md-12">
                                <label for="email" class="col-form-label text-md-end">{{ __('Email Address') }}</label>


                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>



                                <div class="col-md-12">
                                <label for="password" class="col-form-label text-md-end">{{ __('Password') }}</label>


                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>



                                <div class="col-md-12">
                                <label for="password-confirm" class="col-form-label text-md-end">{{ __('Confirm Password') }}</label>


                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Reset Password') }}
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
