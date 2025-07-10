@extends('errors.layouts.app')
@section('title')
    Access Denied
@endsection
@section('content')
    <div class="misc-wrapper text-center">
        <h2 class="mb-1 mt-2">Approval A Waiting :(</h2>
        <p class="mb-4 mx-2">Your registration is pending by an administrator</p>
        <div class="mt-4">
            <img src="{{asset('assets/images/page-error.png')}}" alt="page-misc-error" width="225"
                 class="img-fluid">
        </div>
    </div>
    <p class="text-center mt-2">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();document.getElementById('logout-form').submit();"
           class="btn btn-success">
            <span>Back To Login</span>
        </a>
        <a class="btn btn-primary" href="#">
            <span>FAQ</span>
        </a>
        <a class="btn btn-secondary" href="#">
            <span>Contact Us</span>
        </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    </p>
@endsection
