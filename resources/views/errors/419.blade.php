@extends('errors.layouts.app')
@section('title')
    Session Expired
@endsection
@section('content')
    <div class="misc-wrapper text-center">
        <h2 class="mb-1 mt-2">Session Expired :(</h2>
        <p class="mb-4 mx-2">Oops! 😖 Your session has expired. Please log in again.</p>
        <a href="{{ route('back.to.login') }}" class="btn btn-custom-primary mb-4">Back To Login</a>
        <div class="mt-4">
            <img src="{{ asset('assets/images/page-error.png') }}" alt="session-expired" width="225" class="img-fluid">
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        setTimeout(function () {
            window.location.href = "{{ route('back.to.login') }}"; // Redirect to log in after 5 seconds
        }, 5000);
    </script>
@endpush
