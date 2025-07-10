@extends('backend.layouts.app')
@section('title')
    Profile
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 mb-2">
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-uppercase">
                    <h5 class="card-title mb-0">Profile</h5>
                    <hr>
                </div>
                <div class="card-body">
                    <form action="{{route('profile.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="name">Name</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ti ti-user"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name"
                                               value="{{Auth::user()->name}}">
                                    </div>
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="mobile">Mobile</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ti ti-phone-call"></i></span>
                                        <input type="text"
                                               class="form-control @error('mobile') is-invalid @enderror"
                                               id="mobile" value="{{Auth::user()->mobile}}"
                                               name="mobile">
                                    </div>
                                    @error('mobile')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="email">Email</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                               id="email" value="{{Auth::user()->email}}"
                                               name="email">
                                    </div>
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="password">Password</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text cursor-pointer" id="togglePassword"><i
                                                class="ti ti-eye-off" id="eyeIcon"></i></span>
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" value="">
                                    </div>
                                    <span
                                        class="text-danger">Note : If you don't want to change, leave it</span>
                                    @error('password')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="user_profile">Profile</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ti ti-photo"></i></span>
                                        <input type="file" class="form-control" name="user_profile"
                                               accept="image/*" id="user_profile">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <a href="@if (Auth::user()->hasRole('admin')){{route('admin.dashboard')}} @else {{route('dashboard')}} @endif"
                                   class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            eyeIcon.classList.toggle('ti-eye');
            eyeIcon.classList.toggle('ti-eye-off');
        });

        $(document).ready(function () {
            setTimeout(function () {
                $('#password').val('');
            }, 1000);
        });
    </script>
@endpush
