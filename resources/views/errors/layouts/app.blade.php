<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config('app.name')}} &#124; @yield('title', 'Error')</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/logo.png')}}"/>
    @include('errors.include.styles')
    @stack('styles')
</head>
<body>
<div class="container-xxl">
    @yield('content')
</div>
@include('errors.include.scripts')
@stack('scripts')
</body>
</html>
