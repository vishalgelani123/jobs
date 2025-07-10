<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/logo.png')}}"/>
    <title>{{config('app.name')}} &#124; @yield('title', 'Home')</title>
    @include('backend.include.styles')
    @stack('styles')
    <style>
        .custom-image {
            width: 100%; /* You can set this to a specific value like 400px or a percentage like 50% */
            /*max-height: 1000vh; !* Set a maximum height to avoid excessive stretching *!*/
            display: block; /* Ensures the image takes up the full width of its container */
            margin: 0 auto; /* Center the image horizontally within the modal */
        }
    </style>
</head>
<body>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        @if(Auth::user()->hasRole('admin'))
            @include('backend.include.admin-sidebar')
        @elseif(Auth::user()->hasRole('employer'))
            @include('backend.include.employer-sidebar')
        @else
            @include('backend.include.candidate_sidebar')
        @endif
        <div class="layout-page">
            @include('backend.include.navbar')
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                </div>
                {{--@include('backend.include.footer')--}}
                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="drag-target"></div>
</div>
@include('backend.include.scripts')
@stack('scripts')
</body>
</html>
