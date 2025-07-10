@extends('auth.layouts.app')
@section('title')
    Reset Password
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="{{route('reset.password')}}" class="app-brand-link gap-2">

              <span class="app-brand-logo demo">
                  <img src="{{asset('assets/images/logo.png')}}">
              </span>
                </a>
            </div>
            <h4 class="mb-2 pt-2">Reset Password</h4>
            <form class="mb-3" action="{{route('reset.password.store')}}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group input-group-merge">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                               name="email" readonly
                               placeholder="Enter your email" value="{{Auth::user()->email}}">
                        <span class="input-group-text"><i class="ti ti-mail"></i></span>
                    </div>
                    @error('email')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                    @if(Session::has('error'))
                        <div class="text-danger">{{Session::get('error')}}</div>
                    @endif
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">New Password</label>
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
                    <button class="btn btn-custom-primary d-grid w-100" type="submit">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
@endsection
