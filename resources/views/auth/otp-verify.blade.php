@extends('auth.layouts.app')
@section('title')
    OTP Verify
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                        <img src="{{asset('assets/images/logo.png')}}" alt="logo">
                    </span>
                </a>
            </div>
            <h5 class="col-6 mb-2 pt-2">OTP Verify</h5>
            <div id="resend-opt-message" class="alert
                    {{ Session::has('success') ? 'alert-success' : '' }}
                    {{ Session::has('error') ? 'alert-danger' : '' }}">
                    {{ Session::get('success') ?? Session::get('error') }}
            </div>
            <form id="twoStepsForm" action="{{route('otp.verification')}}" method="POST"
                  class="fv-plugins-bootstrap5 fv-plugins-framework"
                  novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <div class="mb-6 fv-plugins-icon-container">
                    <div
                        class="auth-input-wrapper d-flex align-items-center justify-content-between numeral-mask-wrapper">
                        <input type="tel" name="otp_1"
                               class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                               maxlength="1" autofocus="">
                        <input type="tel" name="otp_2"
                               class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                               maxlength="1">
                        <input type="tel" name="otp_3"
                               class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                               maxlength="1">
                        <input type="tel" name="otp_4"
                               class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                               maxlength="1">
                    </div>
                    <div
                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100 mb-6 waves-effect waves-light mt-2">
                    Verify My Account
                </button>

                <div class="row">
                    <div class="text-left mt-3 col-6">
                        <a href="{{route('back.to.login')}}" class="btn btn-danger text-decoration-none">Back To
                            Login</a>
                    </div>
                    <div class="text-right mt-3 col-6">
                        <a href="#" class="text-primary text-decoration-none" onclick="reSendOtp()">Resend OTP</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function reSendOtp() {
            $.ajax({
                type: 'post',
                url: '{{route('otp.resend')}}',
                data: {
                    '_token': '{{csrf_token()}}'
                },
                success: function (response) {
                    const messageElement = $('#resend-opt-message');
                    messageElement.removeClass('alert-success alert-danger');
                    if (response.status) {
                        messageElement.addClass('alert-success').text(response.message);
                    } else {
                        messageElement.addClass('alert-danger').text(response.message);
                    }
                },
                error: function (error) {
                    const messageElement = $('#resend-opt-message');
                    messageElement.removeClass('alert-success').addClass('alert-danger');
                    messageElement.text('An error occurred while sending the OTP. Please try again later.');
                }
            });
        }

        document.querySelectorAll('.numeral-mask').forEach((input, index, array) => {
            input.addEventListener('input', function () {
                if (this.value.length === this.maxLength && index < array.length - 1) {
                    array[index + 1].focus();
                } else if (this.value.length === 0 && index > 0) {
                    array[index - 1].focus();
                }
            });
        });
    </script>
@endpush
