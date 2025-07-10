@extends('errors.layouts.app')
@section('title')
    Access Denied
@endsection
@section('content')
    <div class="misc-wrapper text-center">
        <h2 class="mb-1 mt-2">Access Denied :(</h2>
        <p class="mb-4 mx-2">Oops! 😖 You have not rights to access requested URL</p>
        <a href="{{route('index')}}" class="btn btn-custom-primary mb-4">Back To Home</a>
        <div class="mt-4">
            <img src="{{asset('assets/images/page-error.png')}}" alt="page-misc-error" width="225"
                 class="img-fluid">
        </div>
    </div>
@endsection
