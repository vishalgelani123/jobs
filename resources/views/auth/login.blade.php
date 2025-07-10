@extends('auth.layouts.app')
@section('title')
    Login
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="{{route('login')}}" class="app-brand-link gap-2">

              <span class="app-brand-logo demo">
                  <img src="{{asset('assets/images/logo.png')}}">
              </span>
                    {{--<span class="app-brand-text demo text-body fw-bold ms-1">{{config('app.name')}}</span>--}}
                </a>
            </div>
            <h4 class="mb-1 pt-2">Welcome to {{config('app.name')}}!</h4>
            <p class="mb-4">Please sign-in to your account</p>
            <form class="mb-3" action="{{route('login')}}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group input-group-merge">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                               name="email"
                               placeholder="Enter your email" value="{{old('email')}}">
                        <span class="input-group-text"><i class="ti ti-mail"></i></span>
                    </div>
                    @error('email')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        <a href="{{ route('password.request') }}">
                            <small>Forgot Password?</small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password"
                               class="form-control @error('password') is-invalid @enderror" name="password"
                               placeholder="............."
                               aria-describedby="password"/>
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    @error('password')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember"
                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-custom-primary d-grid w-100" type="submit">Sign in</button>
                </div>
            </form>
            <p class="text-center">
                <a  href="#">
                    <span>FAQ</span>
                </a>&nbsp;&nbsp;&nbsp;
                <a  href="#">
                    <span>Contact Us</span>
                </a>
            </p>
        </div>
    </div>
@endsection
