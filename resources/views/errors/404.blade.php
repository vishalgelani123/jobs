@extends('errors.layouts.app')
@section('title')
    Page not found
@endsection
@section('content')
    <div class="misc-wrapper text-center">
        <h2 class="mb-1 mt-2">Page not found :(</h2>
        <p class="mb-4 mx-2">Oops! 😖 Requested page not found</p>
        <a href="{{route('index')}}" class="btn btn-custom-primary mb-4">Back To Home</a>
        <div class="mt-4">
            <img src="{{asset('assets/images/page-error.png')}}" alt="page-misc-error" width="225"
                 class="img-fluid">
        </div>
    </div>
@endsection
