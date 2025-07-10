@extends('frontend.layouts.app')
@section('title')
    Vendor Invitation Registration
@endsection
<style>
    .step.active .bs-stepper-circle {
        background-color: #015dab !important;
    }

    .btn-submit {
        background-color: #015dab !important;
        color: #fff !important;
    }
</style>
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-9 col-md-9">
                    <img rel="icon" src="{{asset('assets/images/logo.png')}}" alt="logo" style="width: 138px;">
                </div>
                <div class="col-xl-9 col-lg-9 col-md-9 mt-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <b>Categories:</b>&nbsp;&nbsp;
                            @foreach($categoryArr as $cat)
                                <span class="badge bg-label-primary">{{$cat}}</span>&nbsp
                            @endforeach
                            <br><br>

                            <b>Sub Categories:</b>
                            &nbsp;&nbsp; @foreach($subcategoryArr as $subCat)
                                <span class="badge bg-label-info">{{$subCat}}</span>&nbsp
                            @endforeach
                            <br><br>

                            <b>Vendor Type:</b>
                            &nbsp;&nbsp; <span
                                class="badge bg-label-secondary">{{@$invitationDetail->vendorType->name ? $invitationDetail->vendorType->name : ""}}</span>&nbsp
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-9 mt-3">
                    <div class="bs-stepper wizard-numbered mt-2">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#account-details">
                                <button type="button" class="step-trigger" id="account-details-tab">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Vendor Information</span>
                                    <span class="bs-stepper-subtitle">Add Vendor Information</span>
                                </span>
                                </button>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#personal-info">
                                <button type="button" class="step-trigger" id="personal-info-tab">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">
                                      <span class="bs-stepper-title">Contact Person Information</span>
                                      <span class="bs-stepper-subtitle">Add Contact Person Information</span>
                                    </span>

                                </button>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#password-info">
                                <button type="button" class="step-trigger" id="password-info-tab">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label">
                                      <span class="bs-stepper-title">Password Details</span>
                                      <span class="bs-stepper-subtitle">Add Password Details</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">

                            <form id="preVendorDetailForm" enctype="multipart/form-data" method="post"
                                  action="{{route('store.pre.vendor.invitation.detail',$invitationDetail->invitation_code)}}">
                                @csrf
                                <!-- Account Details -->
                                <div id="account-details" class="content">
                                    <div class="content-header mb-3">
                                        <h6 class="mb-0">Vendor Information</h6>
                                        <small>Enter Vendor Information.</small>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="name" class="form-label">Business Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                   placeholder="Enter Name"
                                                   value="{{$invitationDetail->name}}">
                                            @error('name')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="email" class="form-label">Email<span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                   placeholder="Enter Email"
                                                   value="{{$invitationDetail->email}}">
                                            @error('email')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="mobile" class="form-label">Mobile<span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="mobile" id="mobile"
                                                   placeholder="Enter Mobile Number"
                                                   value="{{old('mobile',$invitationDetail->mobile)}}">
                                            @error('mobile')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                        @foreach($preVendorDetailItem as $item)
                                            <input type="hidden" name="sub_category_id[]"
                                                   value="{{ htmlspecialchars($item) }}">
                                        @endforeach
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="state" class="form-label">State<span
                                                    class="text-danger">*</span></label>
                                            <select id="state" name="state" class="form-control select2">
                                                <option selected disabled value="">Select State</option>
                                                @foreach($states as $state)
                                                    <option value="{{$state->id}}"
                                                            @if($invitationDetail->state_id == $state->id) selected @endif>{{$state->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('state')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="city" class="form-label">City<span
                                                    class="text-danger">*</span></label>
                                            <select id="city" name="city" class="form-control select2">
                                                <option selected disabled value="">Select City</option>
                                                @foreach($cities as $city)
                                                    <option
                                                        value="{{$city->id}}" {{ $city->id==$invitationDetail->city_id ? 'selected' : old('city') }}>{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('city')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="address" class="form-label">Address<span
                                                    class="text-danger">*</span></label>
                                            <textarea id="address" name="address" class="form-control"
                                                      placeholder="Enter Address">{{old('address',$invitationDetail->address)}}</textarea>
                                            @error('address')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="pin_code" class="form-label">Pin Code<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="pin_code"
                                                   id="pin_code"
                                                   placeholder="Enter Pin Code"
                                                   value="{{ @$vendor->pin_code ? $vendor->pin_code : old('pin_code')}}">
                                            @error('pin_code')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 d-flex justify-content-between">
                                            <button type="button" class="btn btn-submit btn-prev" disabled><i
                                                    class="ti ti-arrow-left me-sm-1 me-0"></i>
                                                <span
                                                    class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button class="btn btn-primary btn-next" type="button"
                                                    style="background-color: #015dab !important;"><span
                                                    class="align-middle d-sm-inline-block d-none me-sm-1" type="button">Next</span>
                                                <i class="ti ti-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div id="personal-info" class="content">
                                    <div class="content-header mb-3">
                                        <h6 class="mb-0">Contact Person Info</h6>
                                        <small>Enter Contact Person Info.</small>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="contact_person_name" class="form-label">Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact_person_name"
                                                   id="contact_person_name"
                                                   placeholder="Enter Name Of Contact Person"
                                                   value="{{ @$vendor->name_of_contact_person ? $vendor->name_of_contact_person : old('contact_person_name') }}">
                                            @error('contact_person_name')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="contact_person_email" class="form-label">Email<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact_person_email"
                                                   id="contact_person_email"
                                                   placeholder="Enter Email Of Contact Person"
                                                   value="{{ @$vendor->contact_person_email ? $vendor->contact_person_email : old('contact_person_email') }}">
                                            @error('contact_person_email')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="contact_person_mobile" class="form-label">Mobile Number<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact_person_mobile"
                                                   id="contact_person_mobile"
                                                   placeholder="Enter Mobile Number Of Contact Person"
                                                   value="{{ @$vendor->contact_person_mobile_number ? $vendor->contact_person_mobile_number : old('contact_person_mobile') }}">
                                            @error('contact_person_mobile')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 d-flex justify-content-between">
                                            <button class="btn btn-submit btn-prev" type="button"><i
                                                    class="ti ti-arrow-left me-sm-1 me-0"></i>
                                                <span
                                                    class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button class="btn btn-primary btn-next" type="button"><span
                                                    class="align-middle d-sm-inline-block d-none me-sm-1"
                                                    style="background-color: #015dab !important;">Next</span>
                                                <i class="ti ti-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div id="password-info" class="content">
                                    <div class="content-header mb-3">
                                        <h6 class="mb-0">Password Details</h6>
                                        <small>Enter Password Details.</small>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="password" class="form-label">Password<span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password"
                                                   id="password"
                                                   placeholder="Enter Password" value="">
                                            @error('password')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="password_confirmation" class="form-label">Confirm Password<span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control"
                                                   name="password_confirmation" id="password_confirmation"
                                                   placeholder="Enter Confirm Password" value="">
                                        </div>
                                        <div class="col-12 d-flex justify-content-between">
                                            <button class="btn btn-submit btn-prev" type="button"><i
                                                    class="ti ti-arrow-left me-sm-1 me-0"></i>
                                                <span
                                                    class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button type="submit" class="btn btn-submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-9 mt-3 text-center">
                    <a class="btn btn-success" href="{{route('login')}}">
                        <span>Back To Login</span>
                    </a>
                    <a class="btn btn-primary" href="#">
                        <span>FAQ</span>
                    </a>
                    <a class="btn btn-secondary" href="#">
                        <span>Contact Us</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @php
        $tabErrors = [];
        $vendorInfoErrorKeys = ['name','email','mobile','state','city','address','pincode'];
        $contactPersonErrorKeys = ['contact_person_name','contact_person_email','contact_person_mobile'];
        $passwordErrorKeys = ['password','password_confirmation'];

        $vendorInfoErrorTab = 'false';
        $contactPersonErrorTab = 'false';
        $passwordErrorTab = 'false';
    @endphp

    @if($errors->any())
        @foreach ($errors->messages() as $key => $errorMessages)
            @if(in_array($key,$vendorInfoErrorKeys))
                @php $vendorInfoErrorTab = 'true'; @endphp
            @endif
            @if(in_array($key,$contactPersonErrorKeys))
                @php $contactPersonErrorTab = 'true'; @endphp
            @endif
            @if(in_array($key,$passwordErrorKeys))
                @php $passwordErrorTab = 'true'; @endphp
            @endif
        @endforeach
    @endif
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            let vendorInfoErrorTab = '{{$vendorInfoErrorTab}}';
            let contactPersonErrorTab = '{{$contactPersonErrorTab}}';
            let passwordErrorTab = '{{$passwordErrorTab}}';

            if (vendorInfoErrorTab == 'true') {
                $("#account-details-tab").click();
            } else if (contactPersonErrorTab == 'true') {
                $("#personal-info-tab").click();
            } else if (passwordErrorTab == 'true') {
                $("#password-info-tab").click();
            }
        });

        $('.select2').select2();
        setTimeout(function () {
            let stateId = $("#state").val();
            if (stateId) {
                $.ajax({
                    type: 'post',
                    url: "{{route('cities')}}",
                    data: {
                        state_id: stateId,
                        _token: "{{csrf_token()}}",
                    },
                    success: function (response) {
                        let options = '<option value="" disabled>Select City</option>';
                        let selectedCity = "{{$invitationDetail->city_id}}";
                        $.each(response.data, function (key, city) {
                            if (selectedCity == city.id) {
                                options += '<option selected="selected" value="' + city.id + '">' + city.name + '</option>';
                            } else {
                                options += '<option value="' + city.id + '">' + city.name + '</option>';
                            }
                        });
                        $('#city').html(options);
                    }
                });
            } else {
                $('#city').html('<option value="" disabled>Select City</option>');
            }
        }, 1000);

        $('#state').change(function () {
            let stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    type: 'post',
                    url: "{{route('cities')}}",
                    data: {
                        state_id: stateId,
                        _token: "{{csrf_token()}}",
                    },
                    success: function (response) {
                        let options = '<option value="" disabled>Select City</option>';
                        $.each(response.data, function (key, city) {
                            options += '<option value="' + city.id + '">' + city.name + '</option>';
                        });
                        $('#city').html(options);
                    }
                });
            } else {
                $('#city').html('<option value="" disabled>Select City</option>');
            }
        });
    </script>
@endpush

