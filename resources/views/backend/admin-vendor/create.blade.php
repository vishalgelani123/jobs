@extends('backend.layouts.app')
@section('title')
    Create Vendor Details
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
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Create Vendor Details</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('vendors.store')}}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <h5>Company Detail</h5>
                            <div class="col-md-6 mb-2">
                                <label for="vendor_type" class="form-label">Vendor Type<span
                                        class="text-danger">*</span></label>
                                <select id="vendor_type" name="vendor_type" class="form-control select2">
                                    <option selected disabled value="">Select Vendor Type</option>
                                    @foreach($vendorTypes as $vendorType)
                                        <option @if(old('vendor_type') == $vendorType->id) selected
                                                @endif value="{{$vendorType->id}}">{{$vendorType->name}}</option>
                                    @endforeach
                                </select>
                                @error('vendor_type')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="business_name" class="form-label">Business Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="business_name"
                                       id="business_name" placeholder="Enter Business Name"
                                       value="{{old('business_name')}}">
                                @error('business_name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="pre_vendor_sub_category" class="form-label">Pre Vendor Sub Category<span
                                        class="text-danger">*</span></label>
                                <select id="pre_vendor_sub_category" name="pre_vendor_sub_category[]"
                                        class="form-control select2" multiple>
                                    <option disabled value="">Select Pre Vendor Sub Category</option>
                                    @foreach($preVendorSubCategories as $preVendorSubCategory)
                                        <option value="{{ $preVendorSubCategory->id }}"
                                            {{ (in_array($preVendorSubCategory->id, old('pre_vendor_sub_category', []))) ? 'selected' : '' }}>
                                            {{ $preVendorSubCategory->name }}
                                            ({{ $preVendorSubCategory->preVendorCategory->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pre_vendor_sub_category')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="address" class="form-label">Address</label>
                                <textarea id="address" name="address" class="form-control"
                                          placeholder="Enter Address">{{old('address')}}</textarea>
                                @error('address')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="state" class="form-label">State<span
                                        class="text-danger">*</span></label>
                                <select id="state" name="state" class="form-control select2">
                                    <option selected disabled value="">Select State</option>
                                    @foreach($states as $state)
                                        <option @if(old('state') == $state->id) selected
                                                @endif  value="{{$state->id}}">{{$state->name}}</option>
                                    @endforeach
                                </select>
                                @error('state')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="city" class="form-label">City<span
                                        class="text-danger">*</span></label>
                                <select id="city" name="city" class="form-control select2">
                                    <option selected disabled value="">Select City</option>
                                </select>
                                @error('city')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="pin_code" class="form-label">Pin Code</label>
                                <input type="text" class="form-control" name="pin_code"
                                       id="pin_code" placeholder="Enter Pin Code"
                                       value="{{old('pin_code')}}">
                                @error('pin_code')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="mobile_number" class="form-label">Mobile No (Use for OTP Verification)<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="mobile_number"
                                       id="mobile_number" placeholder="Enter Mobile Number"
                                       value="{{old('mobile_number')}}">
                                @error('mobile_number')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="phone_number_2" class="form-label">Phone Number 2</label>
                                <input type="number" class="form-control" name="phone_number_2"
                                       id="phone_number_2" placeholder="Enter Phone Number 2"
                                       value="{{old('phone_number_2')}}">
                                @error('phone_number_2')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="fax_no" class="form-label">Fax No</label>
                                <input type="text" class="form-control" name="fax_no"
                                       id="fax_no" placeholder="Enter Fax No"
                                       value="{{old('fax_no')}}">
                                @error('fax_no')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="email" class="form-label">Email<span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                       id="email" placeholder="Enter Email"
                                       value="{{old('email')}}">
                                @error('email')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <h5 class="mt-4">Company Contact Person</h5>

                            <div class="col-md-6 mb-2">
                                <label for="name_of_contact_person" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name_of_contact_person"
                                       id="name_of_contact_person" placeholder="Enter Name"
                                       value="{{old('name_of_contact_person')}}">
                                @error('name_of_contact_person')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="contact_person_mobile_number" class="form-label">Mobile Number<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="contact_person_mobile_number"
                                       id="contact_person_mobile_number"
                                       placeholder="Enter Mobile Number"
                                       value="{{old('contact_person_mobile_number')}}">
                                @error('contact_person_mobile_number')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="contact_person_email" class="form-label">Email<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="contact_person_email"
                                       id="contact_person_email"
                                       placeholder="Enter Email"
                                       value="{{old('contact_person_email')}}">
                                @error('contact_person_email')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 text-right mt-4">
                            <a href="{{route('vendors.index')}}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        $('#state').change(function () {
            let stateId = $(this).val();
            fetchCities(stateId);
        });

        let selectedState = '{{old('state')}}';
        if (selectedState != '') {
            fetchCities(selectedState);
        }

        function fetchCities(stateId) {
            let selectedCity = '{{old('city')}}';
            $('#city').empty().html('<option value="" disabled>Select City</option>');
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
                        options += '<option ' + (selectedCity == city.id ? 'selected' : '') + ' value="' + city.id + '">' + city.name + '</option>';
                    });
                    $('#city').html(options);
                }
            });
        }
    </script>
@endpush
